<template>
  <div class="container">
    <div class="h3 mt-3">Welcome to Medella HMS, {{ user?.name }}</div>
    <div class="row mt-lg-4 mt-md-2">
      <div class="col-lg-6 col-md-10 col-sm-12 mt-sm-4">
        <div class="card shadow-sm">
          <div class="card-header h5">Health Transaction within {{ currentYear }}</div>
          <div class="card-body">
            <Line :data="lineData" :options="lineOption" :config="lineConfig" />
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-10 col-sm-12 mt-sm-4">
        <div class="card shadow-sm dcard">
          <div class="card-header h5">Staffs in Clinic</div>
          <div class="card-body">
            <Doughnut
              :options="doughnutOptions"
              :data="doughnutChartData"
              :config="doughnutConfig"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col-lg-3 col-md-6 col-sm-12 mt-sm-3 align-items-center justify-content-center">
        <div class="widgetOne text-center shadow-sm">
          <h5 class="pt-2">Patient Count</h5>
          <h1>{{ totalPatients }}</h1>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mt-sm-3 align-items-center justify-content-center">
        <div class="widgetFour text-center shadow-sm">
          <h5 class="pt-2">Available Bed</h5>
          <h1>{{ totalRoomCount }}</h1>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mt-sm-3 align-items-center justify-content-center">
        <div class="widgetThree text-center shadow-sm">
          <h5 class="pt-2">Born in {{ currentMonth }}</h5>
          <h1>{{ totalBorn }}</h1>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mt-sm-3 align-items-center justify-content-center">
        <div class="widgetTwo text-center shadow-sm">
          <h5 class="pt-2">Dead in {{ currentMonth }}</h5>
          <h1>{{ totalDead }}</h1>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  ArcElement,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement
} from 'chart.js'
import { Doughnut, Line } from 'vue-chartjs'

ChartJS.register(
  CategoryScale,
  LinearScale,
  LineElement,
  BarElement,
  ArcElement,
  PointElement,
  Title,
  Tooltip,
  Legend
)

const user = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))
const clinics = ref([])
const staffInClinic = ref([])
const currentMonth = ref('')
const currentYear = ref('')
const months = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December'
]
const transactions = ref([])
const totalPatients = ref('')
const totalRoomCount = ref('')
const totalBorn = ref('')
const totalDead = ref('')
// 10, 34, 56, 98, 12, 45, 86, 90
onMounted(async () => {
  await getUser()
  await getTransaction()
  await getStaffInClinics()
  await getPatientCount()
  await getAvailableRoomCount()
  await getBornCount()
  await getDeadCount()
  getDate()
})
const getDate = () => {
  let date = new Date()
  currentMonth.value = months[date.getMonth()]
  currentYear.value = date.getFullYear()
}
const getTransaction = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/transactions', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      transactions.value = Object.values(data.data)
      // console.log('Transaction', Array.isArray(transactions.value))
    } else {
      console.log('Error:', data.message)
    }
  } catch (error) {
    console.log(error.message)
  }
}
const getUser = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/me', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      user.value = data.data
      // console.log('USER INFORMATION: ', user.value)
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const getStaffInClinics = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/staff-in-clinic', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()

    if (response.ok) {
      clinics.value = Object.keys(data.data)
      staffInClinic.value = Object.values(data.data)
      // const res = Object.entries(data.data)
      // console.log('Response ', res)
      console.log('clinics : ', data.data)
      console.log('staff list : ', staffInClinic.value)
    } else {
      console.log('ERROR : ', data.message)
    }
  } catch (error) {
    console.log(error.message)
  }
}
const getBornCount = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/born-count', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      totalBorn.value = data.data
    } else {
      console.log('Error: ' + data.message)
    }
  } catch (error) {
    console.log(error.message)
  }
}
const getDeadCount = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/dead-count', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      totalDead.value = data.data
    } else {
      console.log('Error: ' + data.message)
    }
  } catch (error) {
    console.log(error.message)
  }
}
const getPatientCount = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/total-patient', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      totalPatients.value = data.data
      console.log('PATIENT COUNT : ', totalPatients.value)
    } else {
      console.log('Error: ' + data.message)
    }
  } catch (error) {
    console.log(error.message)
  }
}
const getAvailableRoomCount = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/available-room-count', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      totalRoomCount.value = data.data
      console.log('PATIENT COUNT : ', totalPatients.value)
    } else {
      console.log('Error: ' + data.message)
    }
  } catch (error) {
    console.log(error.message)
  }
}
const doughnutChartData = computed(() => ({
  labels: clinics.value,
  datasets: [
    {
      label: 'Staff in Clinics',
      data: staffInClinic.value,
      backgroundColor: [
        '#6BD38AB3',
        '#FFD166B3',
        '#F08080cc',
        '#C9E3F9cc',
        '#B87AFF',
        '#DCBDFF',
        '#7B07FE',
        '#F1E4FF'
      ],
      hoverOffset: 4,
      borderColor: '#343A407f'
    }
  ]
}))

const doughnutOptions = {
  responsive: true,
  maintainAspectRatio: false
}
const doughnutConfig = {
  type: 'doughnut',
  data: doughnutChartData
}
const lineData = computed(() => ({
  labels: months,
  datasets: [
    {
      label: 'Number of Transactions',
      backgroundColor: '#4da8f5',
      data: transactions.value // Access the value of the reactive ref
    }
  ]
}))

const lineOption = {
  responsive: true,
  maintainAspectRatio: false,
  elements: {
    line: {
      tension: 0.3,
      borderColor: '#4DA7F54d'
    }
  }
}
const lineConfig = {
  type: 'line',
  data: lineData
}
</script>
<style scoped>
.card {
  height: 50vh;
}
.widgetOne {
  width: 100%;
  height: 100px;
  background-color: #74b9f3;
  border-radius: 10px;
  border: 3px solid #343a407f;
}
.widgetTwo {
  width: 100%;
  height: 100px;
  background-color: #f08080;
  border-radius: 10px;
  border: 3px solid #343a407f;
}
.widgetThree {
  width: 100%;
  height: 100px;
  background-color: #6bd38a;
  border-radius: 10px;
  border: 3px solid #343a407f;
}
.widgetFour {
  width: 100%;
  height: 100px;
  background-color: #ffd166;
  border-radius: 10px;
  border: 3px solid #343a407f;
}
/* Extra Small devices (portrait phones) */
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  .dcard {
    height: 500px;
    margin-top: 20px;
  }
  .widgetOne,
  .widgetTwo,
  .widgetFour,
  .widgetThree {
    margin-bottom: 10px;
  }
}

/* Small devices (landscape phones, 576px and up) */
@media only screen and (min-width: 576px) and (max-width: 767.98px) {
  /* CSS rules for small screens */
}

/* Medium devices (tablets, 768px and up) */
@media only screen and (min-width: 768px) and (max-width: 991.98px) {
  /* CSS rules for medium screens */
}

/* Large devices (desktops, 992px and up) */
@media only screen and (min-width: 992px) {
  /* CSS rules for large screens */
}
</style>
