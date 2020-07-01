<template>
    <div>
        <v-alert v-for="(item,index) in errors" type="error" :key="index" close-text="Close Alert" >
            {{item}}
        </v-alert>
    </div>
</template>
<script>
    export default {
        props:['errors']
    }
</script>
