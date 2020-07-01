<template>
    <v-dialog v-model="dialog" max-width="500" persistent>
        <v-card>
            <v-card-title>
                {{title}}
            </v-card-title>
            <v-card-text>
                {{message}}
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
<!--                <v-btn color="primary " outlined  @click="close">Нет</v-btn>-->
                <v-btn @click.prevent="close" color="primary " outlined>ОК</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
    export default {
        props:['dialog','title','message',],
        data:()=>({

        }),
        methods:{
            close(){
                this.$emit('close');
            },
        }
    }
</script>
