<template>

    <button @click="add">ADD</button>
    <Chart
        :size="{ width: char.width, height: char.height }"
        :data="data"
        :margin="margin"
        :direction="direction">

        <template #layers>
            <Grid strokeDasharray="2,2" />
            <Line :dataKeys="['insertDate', 'light']" />
        </template>

    </Chart>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { Chart, Grid, Line } from 'vue3-charts'

import axios from "axios";

let plByMonth = []

export default defineComponent({
    name: 'LineChart',
    components: { Chart, Grid, Line },
    mounted() {
        axios.get('https://localhost/api/sensor-reading/list/')
            .then(res => {
                this.data = res.data.res;
            });
    },
    methods: {
        add() {

            console.log('add');

            this.data.push({
                insertDate: 'sdfsf',
                light: 300
            });

            console.log(this.data.length);
        }
    },
    data() {
        return {
            data: [],
            direction: 'horizontal',
            margin: {
                left: 0,
                top: 20,
                right: 20,
                bottom: 0
            },
            char: {
                width: window.innerWidth - 200,
                height: window.innerHeight - 200
            }
        }
    }
})
</script>

<style>
#app {
    color: #2ecc71
}
</style>
