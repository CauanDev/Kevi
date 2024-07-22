<template>
    <LoadingCircle v-if="loading"/>
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40">
        <div class="relative p-4 w-full max-w-lg mx-auto">
            <div class="relative bg-white rounded-lg shadow border border-gray-400">
                <div class="flex flex-col items-center justify-between p-4 md:p-5 border-b ">
                    <div class="w-full flex items-center justify-between border-b border-gray-200 mb-2">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{statusIstance? 'Voce Ja esta Conectado' :"Conectar Numero"}}
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-800 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            data-modal-hide="default-modal" @click="this.$emit('close')">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    <div class="w-full mb-2" v-if="!statusIstance">
                        <div class="flex flex-col gap-3 text-center justify-center items-center">
                            <div class="relative text-center w-[50%]">
                                <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                                <span>
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 18">
                                        <path d="M18 13.446a3.02 3.02 0 0 0-.946-1.985l-1.4-1.4a3.054 3.054 0 0 0-4.218 0l-.7.7a.983.983 0 0 1-1.39 0l-2.1-2.1a.983.983 0 0 1 0-1.389l.7-.7a2.98 2.98 0 0 0 0-4.217l-1.4-1.4a2.824 2.824 0 0 0-4.218 0c-3.619 3.619-3 8.229 1.752 12.979C6.785 16.639 9.45 18 11.912 18a7.175 7.175 0 0 0 5.139-2.325A2.9 2.9 0 0 0 18 13.446Z"/>
                                    </svg>
                                </span>
                                </div>
                                <input class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 " pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="Digite seu Numero" v-model="connect.phone" :disabled="connect.option ==='qrcode'" :value="connect.option === 'qrcode' ? '' : connect.phone"/>
                            </div>
                            <div>
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900">Escolha um Metodo</label>
                                <select id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" v-model="connect.option">
                                  <option value="qrcode" selected>QR code</option>
                                  <option value="code">Código</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <img src="https://imgs.search.brave.com/kuMAH7yhq4Y9eKFXb3NWfP-oaqzYupFblUNm4bHTlzY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9hc3Nl/dHMuc3RpY2twbmcu/Y29tL2ltYWdlcy81/ODU4MmMwMWYwMzQ1/NjJjNTgyMjA1ZmYu/cG5n">
                    </div>
                    <div v-if="img!=='' && !statusIstance">
                        <img
                        :src="img"
                        >
                    </div>

                    <div v-if="code !==''">
                        <div class="relative text-center">
                            <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
                            <span>
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 18">
                                    <path d="M18 13.446a3.02 3.02 0 0 0-.946-1.985l-1.4-1.4a3.054 3.054 0 0 0-4.218 0l-.7.7a.983.983 0 0 1-1.39 0l-2.1-2.1a.983.983 0 0 1 0-1.389l.7-.7a2.98 2.98 0 0 0 0-4.217l-1.4-1.4a2.824 2.824 0 0 0-4.218 0c-3.619 3.619-3 8.229 1.752 12.979C6.785 16.639 9.45 18 11.912 18a7.175 7.175 0 0 0 5.139-2.325A2.9 2.9 0 0 0 18 13.446Z"/>
                                </svg>
                            </span>
                            </div>
                            <input class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 " pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" disabled v-model="code"/>
                        </div>
                    </div>
                    <div class="flex items-center justify-center pt-2 border-t border-gray-200 rounded-b w-full">
                        
                        <button type="button" v-if="statusIstance"
                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2"
                            @click="disconectPhone">Desconectar Numero
                        </button>   
                        <div v-else>
                        <button type="button" 
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2"
                            @click="connectPhone">Conectar Numero
                        </button>
                        <button v-if="img!=='' ||code!=='' " type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none"
                        @click="verifyInstance">Ja Conectei</button>

                        </div>                    


                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import LoadingCircle from '../Loading/LoadingCircle.vue';
import http from "@/services/http"
export default {
    name: "ConfigurationModal",
    emits: ['close'],
    components:{LoadingCircle},
    data() {
        return {
            loading:false,
            statusIstance:null,
            connect:{
                option:"qrcode",
                phone:""
            },
            isConnected:false,
            img:"",
            code:""
        }
    },
    methods: {
        async disconectPhone()
        {
            this.loading = true
            try {
                await http.get('/disconnect')
                this.$emit('close')
            } 
            catch (error) {
                console.log(error)
            }
            this.loading = false
        },
        async connectPhone()
        {
            this.loading = true
            console.log(this.connect)
            try 
            {
                const response = await http.post('/connect',this.connect)
                let data = JSON.parse(response.data)
                console.log(data)
                if(this.connect.option ==="qrcode"){
                    this.img = data.value
                    this.code=""
                }
                else{
                    this.code = data.code
                    this.img=""
                }
                this.loading = false
            } 
            catch (error) 
            {
                console.log(error)
            }
        },
        async verifyInstance() {
            try {
                const data = await http.get('/verify-instance');
                this.statusIstance = data.data.connected
                

            } catch (error) {
                console.log(error)
            }
        }
    },
    mounted(){
        this.verifyInstance()
    }
}
</script>