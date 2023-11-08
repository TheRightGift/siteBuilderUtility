<template>
  <div>
    <div class="container">
      <div class="row">
        <h4 class="header">Our Clients Say</h4>
        <div class="col s12 l4 m4" v-for="review in sortedReviews" :key="review.id">
          <div class="card py-4 px-4">
            <div class="circle" :style="itemStyle(review)">
              {{ getInitials(review.user.names) }}
            </div>
            <h6 class="client-name">{{ review.user.names }}</h6>
            <p class="client-description" v-html="comment(review.comment)"></p>
            <div class="ratings">
              <i v-for="i in review.rating" :key="i" class="fa-solid fa-star" :class="{ active: i <= review.rating }"></i>
              <i v-for="i in 5 - review.rating" :key="i" class="fa-solid fa-star"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<style scoped>
.circle {
  border-radius: 50%;
  width: 5.5vw;
  height: 10.5vh;
  background-color: rgb(202, 200, 200);

  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;

  font-size: 2.5rem;
}
.py-4 {
  padding-top: 4vh;
  padding-bottom: 4vh;
}
.header {
  position: relative;
  font-size: 1.37462rem;
  border-bottom: 1px solid #e7eaf3 !important;
  padding-bottom: 1vh;
}
.header::after {
  width: 84px;

  content: " ";
  height: 2px;
  width: 114px;
  display: block;
  background-color: var(--primary-color);
  position: absolute;
  bottom: -1px;
  left: 0;
}
.card {
  background-color: #ffffff;
  box-shadow: 2px 0 20px rgba(0, 0, 0, 0.06);
  align-items: center;
  justify-content: space-between;
  gap: 1vw;
  display: flex;
  flex-direction: column;
  position: relative;
  margin-top: 0%;
}

.client-name {
  font-weight: 500;
  font-size: 1.5rem;
  font-family: "Jost", sans-serif;
  margin: 0%;
}

.client-description {
  text-align: center;
  margin: 0%;
  color: #807d7d;
  font-family: "Jost", sans-serif;
  font-weight: 400;
}
.ratings .fa-star.active {
  color: gold;
}
</style>
<script>
import templateMixin from "@/mixin/templateMixin";

export default {
  mixins: [templateMixin],
  created() {
    if (this.reviews.length > 0) {
      this.reviews.forEach((item) => {
          item.color = this.getRandomColor();
      });
    }
  },
};
</script>

