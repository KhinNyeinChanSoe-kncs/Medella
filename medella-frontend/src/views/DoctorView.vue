<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2 mt-3">
      <div
        class="col-lg-2 col-md-4 col-sm-4 offset-lg-1 offset-md-1 h3 col-4"
        alt="Doctor List Heading"
      >
        Doctor List
      </div>

      <div class="col-lg-3 col-md-6 offset-md-0 col-sm-8 col-8 offset-lg-5 mb-3">
        <div class="search-container">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-sm"
              id="searchInput"
              v-model="searchKeyword"
              @change="handleKeyDown"
              placeholder="Search by name, email, and phone"
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
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Doctor ID</th>

                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="doctor in doctorList.data" :key="doctor.id">
                  <td class="id-column">{{ doctor.id }}</td>
                  <td>{{ doctor.doctor_id }}</td>
                  <td>{{ doctor.name }}</td>
                  <td>{{ doctor.email }}</td>
                  <td>{{ doctor.phone }}</td>
                  <td>
                    <button class="btn btnDetail btn-sm" @click="getDetails(doctor.id)">
                      <i class="fi fi-rs-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <nav aria-label="Page navigation example">
                  <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                      <a class="page-link" href="#" aria-label="Previous" @click="prevPage">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li
                      class="page-item"
                      v-for="page in lastPage"
                      :key="page"
                      :class="{ active: currentPage === page }"
                    >
                      <a class="page-link" href="#" @click="changePage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                      <a class="page-link" href="#" aria-label="Next" @click="nextPage">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- <div class="col-1"></div> -->
    </div>
    <!-- <ul>
      <li v-for="user in doctorList" :key="user.id">{{ user.name }}</li>
    </ul> -->
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const doctorList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const currentPage = ref(1)
const lastPage = ref()
const paginationLink = ref([])
const searchKeyword = ref('')

onMounted(async () => {
  await getDoctorList()
})
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
  }
}
//get all user start
const getDoctorList = async () => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/doctors?page=${currentPage.value}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      doctorList.value = data.data

      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
//get all user end

//search functions start
const getSearchedDoctorList = async () => {
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)

  try {
    console.log('>>>>>>>>>>>>>>>>>>>>>>here', searchKeyword.value)
    const response = await fetch(
      `http://127.0.0.1:8000/api/search-doctors?page=${currentPage.value}`,
      {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        },
        body: formData
      }
    )
    // console.log('Response:', await response.json())
    if (response.ok) {
      const data = await response.json()
      doctorList.value = data.data

      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
    } else {
      console.log('Error:', response.statusText)
    }
  } catch (e) {
    console.log(e)
  }
}

const onFormSubmit = () => {
  if (searchKeyword.value.trim() === '') {
    getDoctorList()
  } else {
    getSearchedDoctorList()
  }
}
// search functions end

//details function start
const getDetails = (param) => {
  console.log('Parameter:', param)
  router.push({
    path: `/doctors/${param}`
    // props: { userId: param }
  })
}

//details function end

const changePage = async (page) => {
  currentPage.value = page
  await getDoctorList()
}
const nextPage = async () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++
    await getDoctorList()
  }
}
const prevPage = async () => {
  if (currentPage.value > 1) {
    currentPage.value--
    await getDoctorList()
  }
}
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
  width: 150px;
}
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  #searchInput {
    width: 240px;
    color: #343a40;
  }
  #searchInput::placeholder {
    font-size: 14px;
  }
}
</style>
