<template>
  <WrongWarning
  @close="close"
  :title="title"
  v-if="wrongModal"
  />
  <div class="flex justify-center max-h-[420px] overflow-y-auto mt-2">    
      <table class="w-full text-sm text-left rtl:text-right text-black">
        <thead class="text-xs uppercase bg-gray-300 text-center text-black sticky top-0">
          <tr>
            <th v-for="(header, index) in headers" :key="index" class="px-6 py-3 border-b border-gray-400">
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white">
          <tr v-for="(row, rowIndex) in body" :key="rowIndex" class="border-b border-gray-400 text-center">
            <td v-for="(cell, cellIndex) in row" :key="cellIndex" class="px-6 py-4 whitespace-nowrap text-center">
              <span v-if="cellIndex === 'button'" class="flex gap-2">
                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-1 py-1" @click="sendMessage(cell.body)">
                  {{ getButtonText(cell.body) }}
                </button>
                <button type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-1" @click="deleteSchedule(cell.body)">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="white" class="w-4" viewBox="0 0 448 512"><!--! Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc. --><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"/></svg>
                </button>
              </span>
              <span v-else> 
                {{ cell }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
  </div>
</template> 

<script>
import http from "@/services/http"
import WrongWarning from "../Warnings/WrongWarning.vue";
export default {
name: 'NormalTable',
components:{WrongWarning},
emits: ['scheduleNow','deleteSchedule'],
props: {
  headers: {
    type: Array,
  },
  body: {
    type: Array,
  }
},
data(){
  return{
    statusIstance:null,
    wrongModal: false,
    title:""
  }
},
methods: {
  async sendMessage(details) {
    await this.verifyInstance()
    if(this.statusIstance)this.$emit('scheduleNow', details);
    else
    {
      this.wrongModal = true
      this.title="Voce Nao esta Conectado"
    }
  },
  async deleteSchedule(details)
  {
    await this.verifyInstance()
    if(this.statusIstance)this.$emit('deleteSchedule', details);
    else
    {
      this.wrongModal = true
      this.title="Voce Nao esta Conectado"
    }

  },
  close()
  {
    this.wrongModal = false
  },
  getButtonText(cellBody) {   
    return cellBody.status ==="Lembrado" ? "Postar Novamente" : "Postar Agora";
  },
  async verifyInstance() {
    try 
    {
      const data = await http.get('/verify-instance');
      this.statusIstance = data.data.connected
    } 
    catch (error) 
    {
      console.log(error)
    }
  },
}
};
</script>
