import { createRouter, createWebHistory } from 'vue-router';
import HomeView from "@/views/HomeView.vue"
import ScheduleView from "@/views/ScheduleView.vue"

const routes = [
    {
        path:"/",
        name:"Home",
        component: HomeView
    },
    {
        path:"/agenda",
        name:"Schedule",
        component: ScheduleView
    }

]


const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes
  });
export default router;