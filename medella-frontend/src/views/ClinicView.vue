<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="col-lg-3 col-md-5 col-sm-12 offset-lg-1 offset-md-1 h3" alt="User List Heading">
        Clinic List
      </div>

      <div class="col-lg-3 col-md-6 offset-md-0 col-sm-6 col-10 offset-lg-3 mb-3">
        <div class="search-container">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-sm"
              id="searchInput"
              v-model="searchKeyword"
              @change="handleKeyDown"
              placeholder="Search by Clinic Name and Email"
            />
            <button
              class="btn btn-md px-2 actionBtn ms-1"
              type="submit"
              id="searchButton"
              alt="search button"
            >
              <img src="./../assets/search.svg" alt="" />
            </button>
          </form>
          <button
            class="btn btn-primary px-3 ms-2 actionBtn"
            alt="add button"
            @click="createClinic"
          >
            <div class="d-flex">
              <img src="./../assets/plus.svg" class="me-1" alt="" />
              <span style="color: #fff">Add</span>
            </div>
          </button>
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table mx-2">
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
                <tr v-for="clinic in clinicList" :key="clinic.id">
                  <td class="id-column">{{ clinic.id }}</td>
                  <td>{{ clinic.name }}</td>
                  <td>{{ clinic.email }}</td>
                  <td>{{ clinic.phone }}</td>
                  <td>{{ clinic.description }}</td>
                  <td>
                    <button
                      class="btn btnDetail btn-sm me-2s"
                      @click="updateClinic(clinic.id)"
                      v-show="user.role_id === 1"
                    >
                      <img src="./../assets/pencil.svg" alt="" />
                    </button>
                    <button
                      class="btn btnDetail btn-sm"
                      @click="viewClinicStaff(clinic.id)"
                      v-show="
                        clinic.id === user?.doctor?.clinic_id ||
                        clinic.id === user?.medical_staff?.clinic_id ||
                        user.role_id === 1
                      "
                    >
                      <img src="./../assets/overview.svg" alt="" />
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
    <!-- <ul>
        <li v-for="user in clinicList" :key="user.id">{{ user.name }}</li>
      </ul> -->
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const clinicList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const user = ref({})
const searchKeyword = ref('')

onMounted(async () => {
  await getClinicList()
  await getUser()
})
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
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
      // if (user.value.role_id === 4) {
      // }
      console.log('USER INFORMATION: ', user.value)
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
//get all user start
const getClinicList = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/clinics', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      clinicList.value = data.data
      //   console.log('response data', data.data)
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
//get all user end

//search functions start
const getSearchedClinicList = async () => {
  //   console.log('>>>>>>>>>>>>>>>>>>>>>>here')
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)

  try {
    const response = await fetch('http://127.0.0.1:8000/api/search-clinic', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })

    if (response.ok) {
      const data = await response.json()
      console.log('HEREEEEEEEEEEE WE GO AGAINNNNNNNNNNNNNNNNNNNN', data)
      clinicList.value = data.data
      console.log('search result', data)
    } else {
      console.log('Error:', response)
    }
  } catch (e) {
    console.log(e)
  }
}
const onFormSubmit = () => {
  if (searchKeyword.value.trim() === '') {
    getClinicList()
  } else {
    getSearchedClinicList()
  }
}
// search functions end

//details function start
// const getDetails = (param) => {
//   console.log('Parameter:', param)
//   router.push({
//     path: `/users/${param}`
//     // props: { userId: param }
//   })
// }
const createClinic = () => {
  router.push('create-clinic')
}
const updateClinic = (param) => {
  router.push({
    path: `/clinics/${param}/update`
  })
}
const viewClinicStaff = (param) => {
  router.push({
    path: `/clinics/${param}/staff`
  })
}

//details function end

// const changePage = async (page) => {
//   currentPage.value = page
//   await getClinicList()
// }
// const nextPage = async () => {
//   if (currentPage.value < lastPage.value) {
//     currentPage.value++
//     await getClinicList()
//   }
// }
// const prevPage = async () => {
//   if (currentPage.value > 1) {
//     currentPage.value--
//     await getClinicList()
//   }
// }
</script>

<style scoped>
* {
  color: #343a40;
}
.search-container,
.search-container form {
  /* width: 350px; */
  display: flex;
}
#searchInput {
  width: 280px;
  color: #343a40;
}
#searchInput::placeholder {
  color: #b0bec5;
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
