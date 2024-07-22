<template>
  <WrongWarning
  @close="close"
  :title="title"
  v-if="wrongModal"
  />

  <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40">
    <div class="relative p-4 w-full max-w-lg mx-auto">
      <div class="relative bg-white rounded-lg shadow border border-gray-400">
        <div class="flex flex-col items-center justify-between p-4 md:p-5 border-b">
          <div class="w-full flex items-center justify-between border-b border-gray-200 mb-2">
            <h3 class="text-xl font-semibold text-gray-900">
              Selecionar Horário
            </h3>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-800 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="default-modal" @click="this.$emit('close')">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
              </svg>
              <span class="sr-only">Close modal</span>
            </button>
          </div>
  
          <div class="w-full mb-2">
            <div class="w-full flex flex-col gap-3 text-center">
              <div class="w-full">
                <label class="block mb-2 text-sm font-medium text-gray-900">Selecione um Dia</label>
                <input type="date" :min="today" v-model="dates" @input="validateDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>  
              </div>     
              <div class="w-full flex gap-3">
                <div class="w-1/2">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Selecione as Horas</label>
                  <input type="text" v-model="hour" @input="validateHour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ex: 12"/>
                </div>
                <div class="w-1/2">
                  <label class="block mb-2 text-sm font-medium text-gray-900">Selecione os Minutos</label>
                  <input type="text" v-model="minute" @input="validateMin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ex: 35"/>
                </div>
              </div>
            </div>
          </div>
  
          <div class="flex items-center justify-center pt-2 border-t border-gray-200 rounded-b w-full gap-2">
            <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2" @click="createSchedule">Lembrar as {{date}}</button>
            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2" @click="createNow">Lembrar Agora</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</template>
<script>
import { format,parseISO } from 'date-fns';
import WrongWarning from '../Warnings/WrongWarning.vue';
export default{
    name:"ModalSchedule",
    emits: ['close', 'updateDate'],

    components:
    {
      WrongWarning
    },
    data(){
        const yesterday = new Date();
        const hours = yesterday.getHours();
        const minutes = yesterday.getMinutes();
        yesterday.setDate(yesterday.getDate() - 1);
        return {
            today: yesterday.toISOString().split('T')[0],
            aux:{
                date:format(parseISO(yesterday.toISOString().split('T')[0]), 'dd/MM/yyyy'),
                hour: hours,
                minute: minutes
            },
            hour:"00",
            minute:"00",
            dates:"",
            wrongModal:false,
            sucessModal:false,
            date:"00/00/00 00:00",
            title:""
   
        }
    },
    watch:{
        hour()
        {
            this.updateDate()
        },
        minute()
        {
            this.updateDate()

        },
        dates()
        {
            this.updateDate()

        }
    },
    methods:{
    close()
    {
      this.wrongModal = false;
      this.sucessModal = false;
    },
    updateDate()
    {
    this.date = format(parseISO(this.dates), 'dd/MM/yyyy')+" "+this.hour+":"+this.minute

    },
    validateHour() {
      let hour = parseInt(this.hour, 10);
      if (hour < 0 || hour > 23 || this.hour.length>2|| this.hour.length <=0) {
        this.hour = '00';
      }
    },
    validateMin() {
      let minute = parseInt(this.minute, 10);
      if (minute < 0 || minute > 59 || this.minute.length>2 || this.minute.length <=0) {
        this.minute = '00';
      }

    },
    validateDate()
    {
      this.date = format(parseISO(this.dates), 'dd/MM/yyyy')+" "+this.hour+":"+this.minute;
    },
    createSchedule()
    {
        if(!this.dates||this.date ==="00/00/00 00:00"||this.date===" 00:00"){
          this.wrongModal = true
          this.title="Realize alguma Mudanca"
        }
        else
        {
          if((this.aux.date ===format(parseISO(this.dates), 'dd/MM/yyyy') && (this.hour < this.aux.hour)))
          {
            this.wrongModal = true
            this.title="Horas e Minutos Invalido"
          }
          else
          {
            this.subtitle="Horario Registrado com sucesso!"
            this.sucessModal = true
            this.$emit('updateDate', format(parseISO(this.dates), 'dd/MM/yyyy')+" "+this.hour+":"+this.minute);       
          }
        }


    },
    createNow()
    {
      const now = new Date();
      const day = String(now.getDate()).padStart(2, '0');
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const year = now.getFullYear();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      this.$emit('updateDate', `${day}/${month}/${year} ${hours}:${minutes}`);       

    }
    }
}
</script>