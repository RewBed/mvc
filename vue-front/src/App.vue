<template>
    <MainChart />
    <table>
        <thead>
            <tr>
<!--                <th>Дата</th>-->
                <th>Освещение</th>
            </tr>
        </thead>
        <tbody>
            <tr :key="rowIndex" v-for="(row, rowIndex) in data">
<!--                <td>{{ row.insertDate }}</td>-->
                <td>{{ row.light }}</td>
            </tr>
        </tbody>
    </table>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'

import axios from "axios";
import { io } from "socket.io-client";
import MainChart from "./components/MainChart.vue";

export default defineComponent({
    name: 'LineChart',
    components: {MainChart},
    mounted() {
        axios.get('https://localhost/api/sensor-reading/list/')
            .then(res => {
                this.data = res.data.res;

                const socket = io('http://localhost:5000');
                socket.on('eustatosRoom', (message) => {
                    this.data.push(JSON.parse(message));
                    if(this.data.length > 100) {
                        this.data.shift();
                    }
                });

            });
    },
    methods: {},
    data() {
        return {
            data: []
        }
    }
})
</script>

<style>
#app {
    color: #2ecc71
}
</style>
