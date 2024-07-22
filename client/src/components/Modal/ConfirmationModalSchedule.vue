<template>
    <div class="fixed inset-0 flex items-center justify-center bg-opacity-50 z-40">
        <div class="relative p-4 w-[50%]">
          <div class="relative bg-white rounded-lg shadow border border-gray-400">
            <div class="flex items-center justify-between p-4 md:p-5 border-b flex-col">
              <div class="w-full flex border-b border-gray-200 mb-2">
                <h3 class="text-xl font-semibold text-gray-900">
                  Confirmar Agendamento
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-black-200 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="default-modal" @click="this.$emit('close')">
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                  </svg>
                  <span class="sr-only">Close modal</span>
                </button>
              </div>
    
              <div class=" w-full mb-2">
                <div class="w-full flex flex-col gap-3 text-center">
                  <div class="w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Mensagem Escrita</label>
                    <textarea v-model="newSchedule.message" rows="4" class="block p-2.5 w-full text-sm t0 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" :disabled="!edit"></textarea>
                </div>     
                  <div class="w-full">
                    
                      <div class="w-full flex">
                          <div class="w-full">
                              <label class="block text-sm font-medium text-gray-700 mb-2">Dia Selecionado</label>
                              <input type="text" v-model="newSchedule.date"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" :disabled="!edit"/>
                            </div>
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Personagem Selecionado</label>
                                <select v-model="newSchedule.character" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" :disabled="!edit" >
                                    <option v-for="character in characters" :key="character.name" :value="character.name">
                                      {{ character.name }}
                                    </option>
                                  </select>
                           </div>
                      </div>
                  </div>  
                </div>
              </div>
    
              <div class="flex items-center justify-center pt-2 border-t border-gray-200 rounded-b w-full">
                <button type="button"  class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2" @click="createSchedule">Confirmar</button>
                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 " @click="edit = true">Editar</button>
  
            </div>
            </div>
          </div>
        </div>
      </div>
</template>
<script>
import characters from "@/services/images"
import { isValid,isAfter,parse } from 'date-fns';

export default{

    name:"ConfirmationModalSchedule",
    emits: ['close', 'confirmation'],
    props:{
        schedule:{
            required:true
        }
    }, 
    data(){
        return{
            characters: characters,
            newSchedule: this.schedule,
            edit:false
        }
    },
    methods:
    {
        async createSchedule()
        {
            const now = new Date();
            const newScheduleDate = parse(this.newSchedule.date, 'dd/MM/yyyy HH:mm', new Date());
            if(!this.edit)
            {
                this.$emit('confirmation');       

            }
            else
            {
                if ((isValid(newScheduleDate) && (isAfter(newScheduleDate, now)))&&(this.newSchedule.message.length>8 && (/[a-zA-Z]/.test(this.newSchedule.message)))) {
                    this.$emit('confirmation');  
                     
                }               
            }

}
        
    },
    mounted(){
     console.log(this.newSchedule)
    }

}
</script>