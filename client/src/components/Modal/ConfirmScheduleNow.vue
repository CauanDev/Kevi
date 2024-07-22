<template>       
    <div class="fixed inset-0 flex items-center justify-center bg-opacity-50 z-40">
        <div class="relative p-4 w-full max-w-md max-h-full">
         <div class="relative bg-white rounded-lg shadow border border-gray-400">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" @click="this.$emit('close')">
                <svg class="w-3 h-3"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 "  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                <h3 class="mb-5 text-lg font-normal">{{title}}</h3>
                <div class="flex flex-col">
                    <p class="italic">"{{details.newMessage}}"</p>
                    <p>Sera Enviado Para:</p>
                    <p class="italic">{{details.phone}}</p>
                </div>
                <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 " @click="desicion(true)">
                    Sim, eu Tenho
                 </button>
                <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 " @click="desicion(false)">Nao, Nao Tenho</button>
            </div>
        </div>
     </div>
</div>
</template>
<script>
export default{
    name:"ConfirmScheduleNow",
    emits:['desicion','close'],
    props:{
        title:{
          type: String,
        },
        details:{
            type:Object
        }
        
    },
    methods:{
        desicion(value)
        {
            this.$emit('desicion', value);
        }
    }
    
}
</script>