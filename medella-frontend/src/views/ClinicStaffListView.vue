<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-lg-1 col-md-1 col-sm-2 ms-md-2 ms-sm-0">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-5 offset-lg-3 offset-md-2 offset-sm-0 col-8">
        <span id="title" class="fw-medium mx-auto">Clinic Staff List</span>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-5 offset-lg-2 mt-md-2 col-4">
        <select
          class="form-select form-floating"
          v-model="status"
          aria-label="Default select example"
        >
          <option class="mb-md-1" value="all">All</option>
          <option class="mb-md-1" value="doctor">Doctor</option>
          <option class="mb-md-1" value="medical_staff">Medical Staff</option>
        </select>
      </div>
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Role</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="(staff, index) in staffList" :key="index">
                  <td class="id-column">{{ ++index }}</td>
                  <td>{{ staff.name }}</td>
                  <td>{{ staff.email }}</td>
                  <td>{{ staff.phone }}</td>
                  <td>{{ staff.role === 2 ? 'Medical Staff' : 'Doctor' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-1"></div>
    </div>
  </div>
</template>
<script setup>
import router from '@/router'
import { onMounted, ref, watch } from 'vue'
const staffList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const status = ref('all')
onMounted(async () => {
  await getStaffList()
})
const getStaffList = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const formData = new FormData()
    formData.append('status', status.value)
    const response = await fetch(`http://127.0.0.1:8000/api/clinics/${id}/staff`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })
    const data = await response.json()
    console.log('Responsed Staff Data', data.data)
    staffList.value = data.data
  } catch (e) {
    console.log(e)
  }
}
const back = () => {
  router.push(`/clinics`)
}
watch(status, async (newValue, oldValue) => {
  if (newValue != oldValue) {
    await getStaffList()
  }
})
</script>
<style scoped>
.backarrow {
  font-size: 30px;
  color: #343a40;
}
.backarrow:hover {
  color: #4da8f5;
}
.btnBack:active {
  border: 2px solid #fff;
}
#title {
  font-size: 32px;
  color: #343a40 !important;
}

.page-item {
  color: #343a40;
}
.page-link,
.page-link span {
  text-decoration: none;
  color: #343a40;
}
.page-link:hover,
.page-link span:hover {
  color: #74b9f3;
  cursor: pointer;
}
.id-column {
  width: 50px !important;
}
</style>
