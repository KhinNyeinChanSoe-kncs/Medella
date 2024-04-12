<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div
        class="col-lg-5 col-md-6 col-sm-4 offset-lg-1 offset-md-1 col-7 offset-1 h3"
        alt="User List Heading"
      >
        Department List
      </div>

      <div class="col-lg-2 col-md-2 offset-md-2 col-sm-4 offset-sm-3 col-4 offset-lg-4 mb-3">
        <button
          class="btn btn-primary px-3 ms-2 actionBtn"
          alt="add button"
          @click="createDepartment"
        >
          <img src="./../assets/plus.svg" class="me-1" alt="" /> Add
        </button>
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
                  <th scope="col">Description</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="clinic in departmentList" :key="clinic.id">
                  <td class="id-column">{{ clinic.id }}</td>
                  <td>{{ clinic.name }}</td>
                  <td>{{ clinic.email }}</td>
                  <td>{{ clinic.phone }}</td>
                  <td>{{ clinic.description }}</td>
                  <td>
                    <button class="btn btnDetail btn-sm" @click="updateDepartment(clinic.id)">
                      <img src="./../assets/pencil.svg" alt="" />
                    </button>
                  </td>
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
import { ref, onMounted } from 'vue'

const departmentList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))

onMounted(async () => {
  await getDepartmentList()
})

//get all user start
const getDepartmentList = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/departments', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      departmentList.value = data.data
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}

const createDepartment = () => {
  router.push('create-department')
}
const updateDepartment = (param) => {
  router.push({
    path: `/department/${param}/update`
  })
}
</script>

<style scoped>
* {
  color: #343a40;
}

.text-button {
  display: flex;
  align-items: center;
  justify-content: center;
}

.actionBtn {
  background-color: #74b9f3;
  border: 0;
  color: #fff;
}
.actionBtn:hover {
  background-color: #4da8f5;
  color: #fff;
  cursor: pointer;
}
.btnDetail {
  border: 2px solid #c9e3f9;
}

.btnDetail:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnDetail:active {
  border: 2px solid #74b9f3;
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
