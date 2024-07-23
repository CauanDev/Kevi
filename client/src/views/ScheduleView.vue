<template>
    <LoadingCircle v-if="loading"/>

    <!-- Modal that confirm if everything is correct -->
    <ConfirmScheduleNow
    :title="confirmationTitle"
    :details="schedule"
    @desicion="desicion"
    @close="close"
    v-if="modalConfirm"
    />

    <!-- Modal that show that its everything correct-->
    <SucessWarning
    @close="close"
    :title="title"
    v-if="modalSucess"/>

    <!-- Modal that show that something is not correct-->
    <WrongWarning
    @close="close"
    :title="subtitle"
    v-if="modalWrong"/>

    <TitleView :firstPart="'Agendamendos'" :secondPart="'Disponiveis'"/>
    <div class="flex justify-center items-center flex-col">
        <div class="flex text-center gap-2">
            <div class="">
                <label class="block text-sm font-medium text-gray-900">Selecione o Personagem</label>
                <select
                  v-model="filter.character" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                  <option
                  v-for="character in characters"
                  :key="character.img"
                  :value="character.name">
                  {{ character.name }}
                </option>
                <option value="all">Todas as Opções</option>
                </select>
            </div>
            <div class="">
                <label class="block text-sm font-medium text-gray-900">Selecione o Status</label>
                <select
                  v-model="filter.status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                  <option value="Ainda Não Postado">Ainda Não Postado</option>
                  <option value="Lembrado">Lembrado</option>
                  <option value="all">Todas as Opções</option>
                </select>
            </div>
        </div>
        <div class="flex gap-2">
            <div class="flex flex-col ">    
                <label for="startDate" class="text-sm font-medium text-gray-700">Data Início</label>
                <input type="date" v-model="filter.startDate" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm">      
            </div>
            <div class="flex flex-col">
                <label for="endDate" class="text-sm font-medium text-gray-700">Data Final</label>
                <input type="date" v-model="filter.endDate" class="p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm">      
            </div>            
        </div>

        <button type="button" class=" mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center" @click="setFilter" :disabled="loading">
            Aplicar Filtro
            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </button>
    </div>
    <!-- Table that will show every schedule, and it can use some function
    scheduleNow = it will post the schedule in the time the button is pressed
    deleteSchedule = it will delete the schedule -->
    <div class="flex justify-center w-full ">
        <NormalTable 
        :headers="['Mensagem','Data','Personagem','Status','Ações']" :body="body" @scheduleNow="scheduleNow" @deleteSchedule="deleteSchedule"/>
    </div>
</template>
<script>
import LoadingCircle from "@/components/Loading/LoadingCircle.vue"
import SucessWarning from "@/components/Warnings/SucessWarning.vue"
import WrongWarning from "@/components/Warnings/WrongWarning.vue"
import TitleView from "@/components/Title/TitleView.vue"
import NormalTable from "@/components/Table/NormalTable.vue"
import ConfirmScheduleNow from "@/components/Modal/ConfirmScheduleNow.vue"
import http from "@/services/http.js";
import { format } from "date-fns";
import characters from "@/services/images"

export default{
    name:"ScheduleView",
    components: {
        LoadingCircle,TitleView,WrongWarning,SucessWarning,NormalTable,ConfirmScheduleNow
    },
    data(){
        return {
            loading: false,
            title:"",
            subtitle:"",
            characters:characters,
            modalSucess:false,
            modalWrong:false,
            modalConfirm:false,
            confirmationTitle:"",
            body:[],
            schedule:{},
            option:"",
            filter:{
                "character":"all",
                "status":"all"
                
            }
        }
    },
    methods:{
        async setFilter()
        {
            this.loading = true
            try {
                const data = await http.post("/schedule-filter", this.filter)
                if(data.data.length>1)this.setSchedulesTable(data.data)
                else
                {
                    this.subtitle = "Consulta Retornou Zero"
                    this.modalWrong = true
                }
            } 
            catch (error) {
                console.log(error)
            }
            this.loading = false
        },
        async deleteSchedule(value)
        {
            this.modalConfirm = true
            this.schedule = {... value}
            this.option="delete"
            this.confirmationTitle="Deseja Apagar o Lembrete?"
        },
        scheduleNow(value)
        {
            this.modalConfirm = true
            this.schedule = {... value}
            this.option="send"
            this.confirmationTitle="Deseja Postar Agora o Lembrete?"

        },
        async desicion(value)
        {
            this.close()
            if(value)
            {
                this.loading = true
                if(this.option==="send")
                {
                    
                    try {
                        await http.post('/send',this.schedule)                        
                        this.title="Mensagem Enviada com Sucesso"
                        this.modalSucess = true
                    } 
                    catch (error) 
                    {
                        console.log(error)
                        this.subtitle = "Deu Erro no Agendamento"
                        this.modalWrong = true
                    }                    
                }
                else
                {
                    try {
                        await http.post('/destroy',this.schedule)
                        this.title="Lembrete Apagado com Sucesso"
                        this.modalSucess = true
                        
                    } catch (error) {
                        console.log(error)
                        this.subtitle = "Deu em Apagar o Agendamento"
                        this.modalWrong = true
                    }
                }
                this.getSchedules()
                this.loading = false

            }
    
        },
        close()
        {
            this.modalSucess = false
            this.modalWrong = false
            this.modalConfirm = false
        },  
        async getSchedules()
        {
            try {
                const {data} = await http.get('/schedules');
                this.setSchedulesTable(data)
            } 
            catch (error) {
                this.subtitle="Erro ao Procurar"
                this.modalWrong = true
                console.log(error.response.data)
            }
        },
        setSchedulesTable(array)
        {
            this.body= array.map((schedule)=>{
               return{ 
                "message":schedule.newMessage,
                "date":format(new Date(schedule.scheduled_at), "dd/MM/yyyy HH:mm"),
                "character":schedule.character,
                "status":schedule.status,
                "button":{
                    "body":schedule
                }
                
            }})

            
        }
    },
    mounted()
    {
       this.getSchedules()
    }
}
</script>









