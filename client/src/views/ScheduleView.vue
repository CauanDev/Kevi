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

    <!-- Table that will show every schedule, and it can use some function
    scheduleNow = it will post the schedule in the time the button is pressed
    deleteSchedule = it will delete the schedule -->
    <div class="flex justify-center w-full ">
        <NormalTable 
        :headers="['Mensagem','Data','Personagem','Status']" :body="body" @scheduleNow="scheduleNow" @deleteSchedule="deleteSchedule"/>
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
            modalSucess:false,
            modalWrong:false,
            modalConfirm:false,
            confirmationTitle:"",
            body:[],
            schedule:{},
            option:""
        }
    },
    methods:{
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









