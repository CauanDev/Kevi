<template>
    <LoadingCircle v-if="loading"/>
    <!-- Modal that show that its everything correct-->
    <SucessWarning
    @close="close"
    :title="subtitle"
    v-if="sucessModal"/>

    <!-- Modal that show that something is not correct-->
    <WrongWarning
    @close="close"
    :title="title"
    v-if="wrongModal"/>
    <TitleView :firstPart="'Mensagens'" :secondPart="'Geradas'" :lastPart="'por IA'"/>


    <div class="w-full flex justify-center items-center mt-5 flex-col gap-2"> 

    <div class="w-[50%] mb-4 border border-gray-200 rounded-lg bg-gray-100 border-gray-400">
        <div class="px-4 py-2 bg-white rounded-t-lg ">
            <textarea v-model="question" rows="4" class="w-full resize-none px-0 text-sm text-gray-900 bg-white border-0 focus:outline-none" placeholder="Escreva o seu Lembrete" ></textarea>
        </div>
        <div class="flex items-center justify-center px-3 py-2 border-t ">
            <button @click="findDate" class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 hover:bg-blue-800">
                Validar Lembrete
            </button>
        </div>
    </div>

    <div class="relative text-center">
        <div class="absolute inset-y-0 start-0 top-[-16px] flex items-center ps-3.5 pointer-events-none">
        <span v-if="svgPhone">
            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 19 18">
                <path d="M18 13.446a3.02 3.02 0 0 0-.946-1.985l-1.4-1.4a3.054 3.054 0 0 0-4.218 0l-.7.7a.983.983 0 0 1-1.39 0l-2.1-2.1a.983.983 0 0 1 0-1.389l.7-.7a2.98 2.98 0 0 0 0-4.217l-1.4-1.4a2.824 2.824 0 0 0-4.218 0c-3.619 3.619-3 8.229 1.752 12.979C6.785 16.639 9.45 18 11.912 18a7.175 7.175 0 0 0 5.139-2.325A2.9 2.9 0 0 0 18 13.446Z"/>
            </svg>
        </span>
        <span v-else>
            <div class="relative">
                <img class="w-8 rounded-full" :src="src" alt="">
                <span class="top-0 left-5 absolute  w-3.5 h-3.5 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></span>
            </div>
        </span>
        </div>
        <input @blur="findPhone" class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" v-model="schedule.phone" />
        <p class="text-xs" :class="{'text-red-500':!userFound}">{{userFoundText}}</p>
    </div>
    
  
    <div class="flex items-center px-1.5 py-2 rounded-lg border border-gray-300 ">
        <button type="button" class="p-2 cursor-auto">
            <svg class="w-5 " fill="gray"  viewBox="0 0 512 512"><!--! Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc. --><path d="M215.4 96L144 96l-36.2 0L96 96l0 8.8L96 144l0 40.4 0 89L.2 202.5c1.6-18.1 10.9-34.9 25.7-45.8L48 140.3 48 96c0-26.5 21.5-48 48-48l76.6 0 49.9-36.9C232.2 3.9 243.9 0 256 0s23.8 3.9 33.5 11L339.4 48 416 48c26.5 0 48 21.5 48 48l0 44.3 22.1 16.4c14.8 10.9 24.1 27.7 25.7 45.8L416 273.4l0-89 0-40.4 0-39.2 0-8.8-11.8 0L368 96l-71.4 0-81.3 0zM0 448L0 242.1 217.6 403.3c11.1 8.2 24.6 12.7 38.4 12.7s27.3-4.4 38.4-12.7L512 242.1 512 448s0 0 0 0c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64c0 0 0 0 0 0zM176 160l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16s7.2-16 16-16zm0 64l160 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-160 0c-8.8 0-16-7.2-16-16s7.2-16 16-16z"/></svg>
        </button>
        <textarea rows="4" class="resize-none  w-full text-sm text-gray-900 bg-white rounded-lg  focus:ring-blue-500 focus:border-blue-500 " v-model="schedule.newMessage" disabled></textarea>
    </div>


    <div class="relative text-center">
        <div class="absolute inset-y-0 start-0 top-0 flex items-center ps-3.5 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4" fill="gray" viewBox="0 0 448 512"><!--! Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc. --><path d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z"/></svg>        </div>
        <input class="text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" v-model="schedule.date" disabled/>
    </div>
    <button type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2" @click="createSchedule" :disabled="!sheduleValidate()">Criar Lembrete</button>

</div>

<div class="w-full flex justify-center items-center flex-col">
<h1 class="block text-sm font-medium">Escolha o Personagem para a sua Mensagem</h1>
<CarouselView class="w-[80%]"
@updateSlide="updateCurrentSlide"
/>

</div>


</template>
<script>
import characters from "@/services/images"
import LoadingCircle from "@/components/Loading/LoadingCircle.vue"
import CarouselView from "@/components/Carousel/CarouselView.vue"
// import ModalSchedule from "@/components/Modal/ModalSchedule.vue"
import SucessWarning from "@/components/Warnings/SucessWarning.vue"
import WrongWarning from "@/components/Warnings/WrongWarning.vue"
// import ConfirmationModalSchedule from "@/components/Modal/ConfirmationModalSchedule.vue"
import TitleView from "@/components/Title/TitleView.vue"
import http from "@/services/http.js";

export default{
    name:"HomeView",
    components: {
        LoadingCircle,CarouselView,SucessWarning,WrongWarning,TitleView
        //ModalSchedule,ConfirmationModalSchedule
    },
    data(){
        return {
            characters,
            contacts:[],
            loading: false,
            validateShedule:true,
            sucessModal:false,
            confirmationModal:false,
            wrongModal:false,
            svgPhone:true,
            statusIstance:"",
            title:"",
            src:"",
            userFoundText:"Procurar Usuario (Ex: 67984531027)",
            userFound:true,
            question:"",
            subtitle:"",
            schedule:{
                date:"",
                character:"Meme da Internet Ironico e Zueiro",
                message:"",
                newMessage:"",
                phone:""
            },
        }
    },
    methods: {
    formatDate() {
    const daysOfWeek = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    const now = new Date();

    const dayOfWeek = daysOfWeek[now.getDay()];

    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');

  

    

    return `${day}-${month}-${year} ${hours}:${minutes} - HOJE EH ${dayOfWeek}, preste atencao, HOJE EH ${dayOfWeek.toUpperCase()}`;
    },
    updateCurrentSlide(newSlide) {
      this.schedule.character = this.characters[newSlide].name
    },
    async findDate()
    {  
        await this.verifyInstance()
        if(this.statusIstance)
        {
            let date = this.formatDate()
            const isFalse = (data) => {
            return ['false', 'false.'].includes(data.trim().toLowerCase());
            };
            let question_date = "Considere a data atual de hoje "+date+" para a sua resposta. Eu vou te enviar uma descrição de data, como por exemplo: 'tirar o lixo sexta-feira', 'fazer reunião na terça-feira', 'entregar o relatório no próximo mês', ou 'compra de alimentos daqui a uma semana', e você deverá me retornar apenas a data nessa formatação: dd-mm-yyyy HH. Caso eu não forneça um horário, presuma que as horas serão 12:00. Se a descrição da data indicar uma data anterior à data atual, retorne 'false'. Exemplos de frases e como interpretá-las: 'tirar o lixo sexta-feira': Retorne a data da próxima sexta-feira. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 26-07-2024 12:00. 'fazer reunião na terça-feira': Retorne a data da próxima terça-feira. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 23-07-2024 12:00. 'entregar o relatório no próximo mês': Retorne o primeiro dia do próximo mês. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 01-08-2024 12:00. 'compra de alimentos daqui a uma semana': Retorne a data uma semana a partir da data atual. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 29-07-2024 12:00. 'agendar consulta para terça-feira da semana que vem': Retorne a data da terça-feira da semana seguinte. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 30-07-2024 12:00. 'cozinhar no próximo domingo': Retorne a data do próximo domingo. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 28-07-2024 12:00. 'pagar contas em dois dias': Retorne a data dois dias após a data atual. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 24-07-2024 12:00. 'última reunião foi na quinta-feira passada': Retorne a data da última quinta-feira antes da data atual. Exemplo: Se hoje é 22-07-2024, a resposta deve ser 18-07-2024 12:00. 'Ir na academia sexta feira da semana que vem: Se hoje é 22-07-2024, a resposta deve ser 02-08-2024 12:00. ' ir tirar o lixo quinta-feira da semana que vem': Se hoje é 22-07-2024, a resposta devera ser 01-08-2024 12:00. Exemplos de datas inválidas e como tratá-las: 'reunião há três meses': Retorne 'false' se o prazo não for claro ou se for um período de tempo impreciso. 'encontrar com amigos no passado': Retorne 'false' se a data referir-se a um período já passado sem uma data específica. 'evento no dia 31 de fevereiro': Retorne 'false' se a data for impossível, como 31 de fevereiro. 'agendar reunião às 25:00': Retorne 'false' se a hora for inválida, como 25:00. 'compromisso às 12:60': Retorne 'false' se os minutos forem inválidos, como 60 minutos. 'evento no dia 29-02-2023 às 24:00': Retorne 'false' se a data e a hora forem impossíveis, como 24:00. Se você não conseguir gerar uma data a partir da descrição fornecida ou se a descrição não corresponder a uma data reconhecível, retorne 'false'. A frase para ser analisada: "+this.question+"."
             let question_message = 
            "Faça o seguinte: abaixo vou te mandar um texto e você deverá reescrever o texto no imperativo, e o texto devera se referir ao dia atual, somente ao dia atual, ou seja A HOJE, sem possuir nenhum sujeito, e sem usar aspas. Reescreva-o como se fosse o '"+this.schedule.character+"' dizendo-o. Seja o mais real possível, e inclua frases relacionadas ao personagem para que pareça que é realmente o personagem escrevendo. Use emojis que tenham relação com o personagem. O texto deverá ter alguma frase referente ao personagem, SE EMPENHE EM ESCOLHER ALGUMA FRASE FAMOSA DO PERSONAGEM PARA ENCAIXAR NO TEXTO. Todos os detalhes relacionados a datas devem ser substituídos por 'hoje', garantindo que a ação descrita na frase ocorra no dia atual. OU SEJA, A FRASE DEVERA SER ESCRITA COMO SE O LEMBRETE FOSSE PARA HOJE, O DIA ATUAL. VOCE DEVERA MANDAR UM TEXTO CONTENDO SOMENTE UM TEXTO ESCRITO COM O LEMBRETE FEITO PARA HOJE!!!. Exemplos de como reescrever o texto: Cuide dos amigos e compre a comida do passarinho hoje de manhã 🐦💪. Sorria para um novo dia e prepare tudo direitinho! Busque a comida do passarinho hoje de manhã! 🌄🐦. Com determinação e um sorriso no rosto, busque a comida do passarinho hoje de manhã! 🌞🐦. Nada pode parar o espírito de luta! Compre a comida para o passarinho hoje de manhã! 💪🐦. Exemplos variados: Mesmo que seja amanhã, compre a comida do passarinho hoje! 🐦🚀. Não importa se é na próxima semana, garanta a comida do passarinho hoje! 🐦🔥. Mesmo que o prazo fosse para o mês que vem, busque a comida do passarinho hoje! 🐦🌟. Resolva tudo hoje o que estava previsto para daqui a dias! Busque a comida do passarinho hoje de manhã! 🐦⚡. O que estava planejado para depois, faça hoje! Compre a comida do passarinho hoje de manhã! 🐦🚀. Se o evento fosse na próxima sexta-feira, participe hoje! 🎉🗓️. Mesmo se o prazo fosse para daqui a uma semana, realize a ação hoje! 🕒✨. Segue o texto: '"+this.question+"'. Se o texto não contiver palavras irreconhecíveis no vocabulário brasileiro, retorne false. SE VC NAO ENTENDER RETORNE FALSE."
            
            console.log(this.schedule.character)
            
        
            let body={
            "text":question_message,
            "date":question_date }

            if(this.question.length>=4)
            {
                this.loading = true
                try {
                    const data =  await http.post('/question',{"body":body})    
                    
                    if (isFalse(data.data.text)||isFalse(data.data.date))
                    {
                        this.wrongModal = true

                        if(isFalse(data.data.text)&&isFalse(data.data.date))this.title="Por Favor Verifique o Lembrete Digitado"
                        else
                        {
                            if(isFalse(data.data.text))this.title="Digite alguma coisa para ser Lembrada"                    
                            if(isFalse(data.data.date))this.title="Por Favor Digite alguma Data no Lembrete"                        
                        }                    
    
                    }
                    else
                    {
                        this.schedule.message = this.question
                        this.schedule.newMessage = data.data.text
                        this.schedule.date = data.data.date.replace(/-/g, '/');
                    }
                } catch (error) {
                    console.log(error)
                }
                this.loading = false
            }
        }

    },
    async findPhone()
    {
        this.loading = true
        await this.verifyInstance()
        if(this.statusIstance)
        {
            if(/[a-zA-Z]/.test(this.schedule.phone))
            {
                const result = this.contacts.find(item=>item.name===this.schedule.phone)
                if(result)
                {
                    try {
                        const data = await http.post('/verify-contact',{"phone":result.phone})
                        this.src = data.data.link
                        this.userFound = true
                        this.userFoundText = "Usuario Encontrado"
                        this.svgPhone = false;                
                        this.schedule.phone = result.phone.slice(2);    
                    } 
                    catch (error) {
                        console.log(error)
                    }


                }
                else 
                {
                    this.userFoundText = "Numero Nao Possui Whatsapp"
                    this.src=""
                    this.userFound = false
                    this.svgPhone = true;
                    this.schedule.phone=""
                }
            }
            else
            {
                if(this.schedule.phone>8)
                {
                    if(this.statusIstance)
                    {
                        try {
                            
                            const data = await http.post('/verify-contact',{"phone":this.schedule.phone})
                            if(data.data.user)
                            {
                                this.src = data.data.link
                                this.userFound = true
                                this.userFoundText = "Usuario Encontrado"
                                this.svgPhone = false;

                            }
                            else
                            {
                                this.userFoundText = "Numero Nao Possui Whatsapp"
                                this.src=""
                                this.userFound = false
                                this.svgPhone = true;
                                this.schedule.phone=""
                                
                            }
                        } 
                        catch (error) {
                            console.log(error)
                        }                    
                    }
                    else
                    {
                        this.userFoundText = "Voce Nao esta Conectado"
                        this.userFound = false
                        this.svgPhone = true;
                        this.schedule.phone=""
                    }
                
                }            
            }            
        }


        this.loading = false

    },
    async verifyInstance() {
        try {
            const data = await http.get('/verify-instance');
            this.statusIstance = data.data.connected
        } catch (error) {
            console.log(error)
        }
    },
    sheduleValidate()
    {
        this.validateShedule = !Object.values(this.schedule).some(value => value === "");
        return this.validateShedule
        
    },
    close()
    {
        this.sucessModal = false;
        this.wrongModal = false;
    },
    async createSchedule()
    {
            
        this.close()
        await this.verifyInstance()
        if(this.statusIstance)
        {
            this.loading = true;
            try {
            const {data}= await http.post('/store-schedule',this.schedule)
            if(data.store)
            {
                this.date = this.schedule.date
                this.loading = false;
                this.subtitle = "Lembrete Adicionado com Sucesso"
                this.sucessModal = true           
                this.userFoundText = "Procurar Usuario (Ex: 67984531027)"            
                Object.keys(this.schedule).forEach(key => {
                    this.schedule[key] = ""; 
                });
            }
            } 
            catch (error) 
            {
                this.title="Houve um ao Adicionar o Lembrete"
                this.wrongModal = true;
            }    
            this.loading = false        
        }
        else
        {
        this.wrongModal = true
        this.title="Voce Nao esta Conectado"
        }

    },
    async getContactName()
    {
        await this.verifyInstance()
        if(this.statusIstance)
        {
            try {
                const data = await http.get('/contact');
                this.contacts = JSON.parse(data.data)
            } catch (error) {
                console.log(error)
            }            
        }

    }
  },
  mounted()
  {
    this.getContactName()
  }
}
</script>