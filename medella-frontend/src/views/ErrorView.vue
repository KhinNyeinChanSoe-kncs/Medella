<template>
  <div class="container text-center mt-5">
    <div class="img-container">
      <img src="./../assets/frame.svg" alt="frame" class="img" />
      <div class="content">
        <iframe
          src="https://lottie.host/embed/2f4012e8-35ff-456e-b4a8-f9ec0deac58d/o39rerqFGu.json"
        ></iframe>
        <h2>404 - Page not found</h2>
      </div>
    </div>
  </div>
</template>
<script setup>
// import { useRoute } from 'vue-router'

// const route = useRoute()
</script>
<style scoped>
/* Default styles for desktop */
.img {
  width: 500px;
  height: 600px;
}

/* .img-container {
  position: relative;
} */

.img {
  display: block;
  width: 100%; /* Ensure the image fills its container */
}

.content {
  position: absolute;
  top: 45%;
  left: 50%;
  transform: translate(-50%, -50%);
}

/* Media query for tablets */
@media screen and (max-width: 768px) {
  .img {
    width: 400px;
    height: 480px;
  }
}

/* Media query for mobile devices */
@media screen and (max-width: 480px) {
  .img {
    width: 300px;
    height: 450px;
  }

  .content {
    top: 50%; /* Adjust positioning for smaller screens */
    margin-top: 5px;
  }
}

.img-container {
  position: relative;
}

.img {
  display: block;
  width: 100%;
}

.content {
  position: absolute;
  top: 45%;
  left: 50%;
  transform: translate(-50%, -50%);
}
h2 {
  font-size: 18px;
}
</style>
