<template>
<div class="w-full flex justify-center items-center mt-4">
    <div class="bg-[#ccfbef] border-2 border-[#9cf4e4] rounded-2xl px-3 py-1.5 text-xl">
        {{this.firstPart}} <span class="text-[#1c6054] font-bold">{{this.secondPart}}</span> {{this.lastPart}}
    </div>
</div>    
</template>
<script>
export default{
    name:"TitleView",
    props:{
        firstPart:String,
        secondPart:String,
        lastPart:String
    }
}
</script>