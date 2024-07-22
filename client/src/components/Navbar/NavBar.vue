<template>
    <ConfigurationModal @close="close" v-if="modalConfiguration" />
    <nav class="bg-white border-b-2 border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="https://kevi.com.br" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="https://kevi.com.br/wp-content/uploads/2024/03/Logotipo-Kevi-Retencao-de-Clientes.svg"
                    class="h-8" alt="Flowbite Logo" />
            </a>
            <button type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 "
                @click="mobile = !mobile">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15" />
                </svg>
            </button>
            <div class="w-full md:block md:w-auto" :class="{ 'hidden': !mobile }">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white ">
                    <li>
                        <router-link class="flex items-center gap-x-3.5 py-2 px-2.5 text-xl font-medium cursor-pointer"
                            :to="{ name: 'Home' }" :class="{ 'text-[#044c3c]': isActive('/') }">
                            Home
                        </router-link>
                    </li>
                    <li>
                        <router-link class="flex items-center gap-x-3.5 py-2 px-2.5 text-xl font-medium cursor-pointer"
                            :to="{ name: 'Schedule' }" :class="{ 'text-[#044c3c]': isActive('/agenda') }">
                            Agenda
                        </router-link>
                    </li>
                    <li class="flex justify-center" @click="modalConfiguration = true">
                        <svg  xmlns="http://www.w3.org/2000/svg" class="w-6 cursor-pointer"
                            viewBox="0 0 512 512"><!--! Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2024 Fonticons, Inc. -->
                            <path
                                d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z" />
                        </svg>
                        <span class="relative flex h-3 w-3 top-[5px] right-[8px]">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                :class="{ 'bg-red-500': !statusIstance, 'bg-green-400': statusIstance }"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3"
                                :class="{ 'bg-red-500': !statusIstance, 'bg-green-400': statusIstance }"></span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</template>
<script>
import { useRoute } from 'vue-router';
import http from "@/services/http"
import ConfigurationModal from "@/components/Modal/ConfigurationModal.vue"

export default {
    name: "NavBar",
    components: {
        ConfigurationModal
    },
    data() {
        return {
            mobile: false,
            statusIstance: false,
            modalConfiguration: false,
        }
    },
    methods: {
        async verifyInstance() {
            try {
                const data = await http.get('/verify-instance');
                this.statusIstance = data.data.connected
            } catch (error) {
                console.log(error)
            }
        },
        close() {
            this.modalConfiguration = false
            this.verifyInstance()
        }
    },
    setup() {
        const route = useRoute();
        const isActive = (path) => {
            return route.path === path;
        };

        return { isActive };
    },
    mounted() {
        this.verifyInstance()
    }
}
</script>