<template>
    <div class="row d-flex justify-content-center">
        <div class="col-8">
            <LineChart
                id="hotel-booking-chart"
                :options="chartOptions"
                :data="chartData"
            />
            <div class="d-flex flex-row-reverse bd-highlight">
                <p><strong>Total Amount: </strong> {{ totalAmount }}</p>
            </div>
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button @click.prevent="filterDuration('current_week')" type="button" class="btn btn-primary">Current week</button>
                    <button @click.prevent="filterDuration('current_month')" type="button" class="btn btn-primary">Current month</button>
                    <button @click.prevent="filterDuration('last_month')" type="button" class="btn btn-primary">Last month</button>
                    <button @click.prevent="filterDuration('current_year')" type="button" class="btn btn-primary">Current year</button>
                    <button @click.prevent="filterDuration('last_year')" type="button" class="btn btn-primary">Last year</button>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import { Line as LineChart} from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    CategoryScale,
    PointElement
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    CategoryScale,
    PointElement
)
export default {
    components: { LineChart },
    data() {
        return {
            data: [],
            totalAmount: 0,
            hotelId: 30,
            duration: 'current_week',
            chartOptions: {
                responsive: true
            }
        }
    },
    computed: {
        // a computed getter
        chartData: function () {
          return {
              datasets: [
                  {
                      label: 'Booking Statistics',
                      backgroundColor: '#f87979',
                      data: this.data
                  }
              ]
          };
        }
    },
    mounted() {
        this.filterDuration();
    },
    methods:{
        filterDuration(duration=null){
            if (duration) this.duration = duration;
            axios.get('/fetch-bookings/'+this.hotelId,{
                params: {
                    duration: this.duration
                }
            }).then(r=>{
            this.data = r.data.data;
            this.totalAmount = r.data.totalAmount;
            })
        }
    }
}
</script>
