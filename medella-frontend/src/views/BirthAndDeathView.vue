<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div
        class="col-lg-4 col-md-4 col-sm-12 col-12 offset-lg-1 offset-md-1 h3 mt-2"
        alt="Doctor List Heading"
      >
        Birth and Death List
      </div>

      <div class="col-lg-5 col-md-6 offset-md-0 col-sm-7 col-10 offset-lg-2 mb-3 mt-2">
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
              class="btn btn-md px-2 actionBtn"
              type="submit"
              id="searchButton"
              alt="search button"
            >
              <img src="./../assets/search.svg" alt="" />
            </button>
          </form>
          <!-- <form action=""> -->
          <select
            class="form-select form-select-sm ms-2"
            @change="getPatientList"
            v-model="selectedStatus"
            aria-label="Small select example"
          >
            <option value="all">All</option>
            <option value="born">Born</option>
            <option value="death">Death</option>
          </select>
          <!-- </form> -->
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm">
          <div class="card-body overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Patient ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Parent Name</th>
                  <th scope="col">Status</th>
                  <th scope="col">Date</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="(patient, index) in patientList" :key="index">
                  <td class="id-column">{{ ++index }}</td>
                  <td>{{ patient?.patient_id }}</td>
                  <td>{{ patient?.user_name }}</td>
                  <td>{{ patient?.patient_parent_name }}</td>
                  <td>
                    <span
                      class="born text-center px-2 py-1"
                      v-show="
                        patient.patient_born_status !== null &&
                        typeof patient.patient_born_status !== 'undefined' &&
                        patient.patient_born_status !== 0
                      "
                    >
                      {{ patient.patient_born_status === 1 ? 'Born' : '' }}
                    </span>

                    <span
                      class="death text-center px-2 py-1 ms-1"
                      v-show="
                        patient.patient_dead_status !== null &&
                        typeof patient.patient_dead_status !== 'undefined' &&
                        patient.patient_dead_status !== 0
                      "
                    >
                      {{ patient.patient_dead_status === 1 ? 'Dead' : '' }}
                    </span>
                  </td>

                  <td>
                    <div class="d-flex">
                      <div
                        class="bornDateSign mt-1 me-2"
                        v-show="patient?.patient_born_date !== null"
                      ></div>
                      {{ patient?.patient_born_date }}
                    </div>

                    <div class="d-flex mt-1">
                      <div
                        v-show="patient.patient_dead_date !== null"
                        class="deadDateSign mt-1 me-2"
                      ></div>
                      {{ patient?.patient_dead_date }}
                    </div>
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
      <div class="col-1"></div>
    </div>
    <!-- <ul>
      <li v-for="user in doctorList" :key="user.id">{{ user.name }}</li>
    </ul> -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const patientList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const selectedStatus = ref('all')
const searchKeyword = ref('')
const currentPage = ref(1)
const lastPage = ref()
const paginationLink = ref([])

onMounted(async () => {
  await getPatientList()
})
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
  }
}
const getPatientList = async () => {
  try {
    const formData = new FormData()
    formData.append('status', selectedStatus.value)
    const response = await fetch(
      `http://127.0.0.1:8000/api/born-dead/patients?page=${currentPage.value}`,
      {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        },
        body: formData
      }
    )
    const data = await response.json()
    if (response.ok) {
      patientList.value = data.data.data
      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
      console.log('born and dead >>>>>>>>>>', data.data)
    } else {
      const data = await response.json()
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e.message)
  }
}
const getSearchedPatientList = async () => {
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)
  formData.append('status', selectedStatus.value)

  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/born-dead/patients/search?page=${currentPage.value}`,
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
      patientList.value = data.data.data
      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
      console.log('Searched Result', data.data)
    } else {
      console.log('Error:', response.statusText)
    }
  } catch (e) {
    console.log(e)
  }
}

const onFormSubmit = () => {
  if (searchKeyword.value.trim() === '') {
    getPatientList()
  } else {
    getSearchedPatientList()
  }
}
// search functions end
</script>

<style scoped>
* {
  color: #343a40;
}

.search-container,
.search-container form {
  display: flex;
}
#searchInput {
  width: 280px;
  color: #343a40;
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
.born {
  color: #4da8f5;
  font-size: 12px !important;
  /* border: 1px solid #4da8f5; */
  background-color: #4da7f54d;
  border-radius: 25px;
}
.death {
  color: #f65454;
  font-size: 12px !important;
  /* border: 1px solid #4da8f5; */
  background-color: #f654544d;
  border-radius: 25px;
}
.bornDateSign {
  background-color: #4da8f54d;
  border: 1px;
  width: 15px;
  height: 15px;
  border-radius: 50%;
}
.deadDateSign {
  background-color: #f654544d;
  width: 15px;
  height: 15px;
  border-radius: 50%;
}
/*
.btnDetail {
  border: 2px solid #c9e3f9;
}

.btnDetail:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnDetail:active {
  border: 2px solid #74b9f3;
} */
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
  width: 50px;
}
#searchInput::placeholder {
  color: #b0bec5;
}
</style>
