<template>
  <div @click="showHeroEditor">
    <div class="carousel carousel-slider" id="promo" v-if="defaultHero === 1">
      <div
        class="carousel-item"
        :href="`#` + index"
        :id="index"
        v-for="(promo, index) in heroSeeder"
        :key="index"
      >
        <div class="overlay"></div>
        <div class="container carouselContainer">
          <div class="flex h-inherit justify-between items-center">
            <div>
              <h4 class="heading m-0 fw-700">
                {{ promo.title }}
              </h4>
              <p class="desc">{{ promo.description }}</p>
              <button
                class="btn startNowBtn fw-700 capitalize waves waves-effect flex items-center justify-center"
              >
                <router-link :to=" loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: heroSeeder.type == `welcome` ? `all` : heroSeeder.title ?? `offer` }, query: { additionalOfferData: heroSeeder.type == `welcome` ? `all` : heroSeeder.id ?? `offer_id` } }">
                  <span>Start Buying</span>
                </router-link>
              </button>
            </div>
            <div class="">
              <img :src="imageUrlWithTimestamp" class="responsive-img" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="hero"
      :style="{ backgroundImage: `url(${imageUrlWithTimestamp})` }"
      v-if="defaultHero == undefined"
    >
      <div class="heroInner">
        <h1 class="heroMainHeading">
          {{ heroSeeder.title }}
        </h1>
        <p>
          {{ heroSeeder.description }}
        </p>
        <div class="heroCtaContainer">
          <router-link class="cta" :to=" loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: heroSeeder.type == `welcome` ? `all` : heroSeeder.title ?? `offer` }, query: { additionalOfferData: heroSeeder.type == `welcome` ? `all` : heroSeeder.id ?? `offer_id` } }" style="cursor: pointer">
            Shop Collection
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import templateMixin from "@/mixin/templateMixin";

export default {
  computed: {
    imageUrlWithTimestamp() {
      // Append the timestamp as a query parameter to the image URL
      if (this.defaultHero == undefined) {
        return `${this.heroSeeder.image}?t=${this.timestamp}`;
      } else {
        return `${this.heroSeeder[this.index].image}?t=${this.timestamp}`;
      }
    },
  },
  data() {
    return {
      index: 0,
      heroSeeder: [
        {
          "id": 1,
          "description": "Lorem Ipsum statica",
          description: "The new Standard",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/416X420/img1.png",
          "subtitle": "The Best Standard Bow Ties",
          "title": "Bow Tie Galore",
          "type": "welcome",
          "created_at": "2023-10-25T09:40:26.000000Z",
          "updated_at": "2023-11-03T14:36:51.000000Z"
        },
        {
          "id": 2,
          description: "The new Standard",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/416X420/img2.png",
          "subtitle": "The Best Standard Bow Ties",
          "title": "Bow Tie Galore",
          "type": "welcome",
          "created_at": "2023-10-25T09:40:26.000000Z",
          "updated_at": "2023-11-03T14:36:51.000000Z"
        },
      ],
      imgDimensionWidth: 416,
      imgDimensionHeight: 420,
    };
  },
  mounted() {
    if (this.hero.length > 0) {
      this.heroSeeder = this.hero;
    }
  },
  mixins: [templateMixin],
};
</script>
<style>


.flex {
  display: flex;
}
.justify-between {
  justify-content: space-between;
}
.items-center {
  align-items: center;
}
.justify-center {
  justify-content: center;
}
/* .flex-col {
                flex-direction: column;
            } */
.carousel .indicators .indicator-item.active {
  background-color: var(--primary-color) !important;
  width: 5vh !important;
  border-radius: 5px;
}
.carousel .indicators {
  left: 5% !important;
  text-align: left !important;
}
.carousel .indicators .indicator-item {
  background-color: #bcbcbc !important;
}
.tabs .indicator {
  background-color: var(--primary-color) !important;
}
@media only screen and (min-width: 1024px) {
  .carousel .indicators {
    left: 25% !important;
    text-align: left !important;
  }
}
@media only screen and (min-width: 768px) and (max-width: 1023px) {
  .carousel .indicators .indicator-item.active {
    width: 3vh !important;
  }
}
@media only screen and (max-width: 767px) {
  .container {
    width: 95% !important;
  }
}
</style>
<style scoped>
sup {
  font-size: 60%;
}
.dblock {
  display: block;
  font-weight: 300;
  font-size: 0.81288rem;
}
.desc.amount {
  line-height: 1;
  margin-top: 3vh;
  margin-bottom: 2vh;
}
.discount {
  font-size: 3.12462rem;
  font-weight: 700;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}

.h-inherit {
  height: inherit;
}
.overlay {
  position: absolute;
  /* background-color: #00000059; */
  width: 100%;
  height: 100%;
  color: white;
}
#one {
  /* background-image: url("https://transvelo.github.io/electro-html/2.0/assets/img/1920X422/img1.jpg"); */
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  /* position: relative; */
}

#two {
  /* background-image: url("https://transvelo.github.io/electro-html/2.0/assets/img/1920X422/img1.jpg"); */
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
}
.heading,
.desc {
  color: var(--secondary-color);
  line-height: normal;
  letter-spacing: 0.2px;
  z-index: 1;
  margin: 0;
}
.heading {
  font-size: 3.99962rem;
  line-height: 3.56212rem;
  font-weight: 300;
  margin-bottom: 1vh;
}
.desc {
  margin-top: 0;
  font-family: "Montserrat", sans-serif;
  font-size: 0.938rem !important;
  width: 523px;
  margin-bottom: 1vh;
  font-weight: 700;
}
.startNowBtn {
  padding: 20px 35px;
  background-color: var(--primary-color);
  font-family: "Poppins", sans-serif;
  font-size: 1.00012rem;
  border-radius: 5px;
  text-transform: capitalize;
  color: #333e48;
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
.startNowBtn span {
  color: #333e48;
}
/* Left and Right scroll buttons */
.carousel-control-prev,
.carousel-control-next {
  font-size: 24px;
  color: #333e48;
  width: 40px;
  height: 40px;
  line-height: 40px;
  border-radius: 50%;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
}

.carousel-control-prev {
  left: 20px;
}

.carousel-control-next {
  right: 10px;
}
.carousel {
  height: 450px !important;
  background-image: url("https://transvelo.github.io/electro-html/2.0/assets/img/1920X422/img1.jpg");
  background-position: center;
  text-transform: uppercase;
  background-repeat: no-repeat;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}

.carouselContainer {
  height: 100%;
}

/* For parallax */
.hero {
  width: 100%;
  margin: 0 auto;
  display: flex;
  align-items: center;
  height: 90vh;
  background-color: var(--primary-color);
  background-position: center right;
  background-repeat: no-repeat;
  background-size: contain;
}
.heroInner {
  width: 34%;
  margin-left: 13vw;
}
.heroInner h1 {
  color: #fff;
  font-family: Fahkwang;
  font-size: 8.5rem;
  font-style: normal;
  font-weight: 600;
  line-height: 8.5rem; /* 100% */
  margin: 0;
}
.heroInner p {
  color: var(--secondary-color);
  font-family: 'Montserrat', sans-serif;
  font-size: 1.375rem;
  font-style: normal;
  font-weight: 600;
  line-height: 1.6rem;
}
.heroCtaContainer {
  margin-top: 7vh;
}
.heroCtaContainer .cta {
  padding: 1vh 2vw;
  text-transform: uppercase;
  border: thin solid#fff;
  background-color: #fff;
  color: #000;
}

@media only screen and (min-width: 1024px) {
  .carouselContainer {
    width: 50%;
  }
  .mt-10 {
    margin-top: 10vh;
  }
}
@media only screen and (min-width: 768px) and (max-width: 1023px) {
  .carousel {
    height: 368px;
  }
  .heading {
    font-size: 2rem;
  }
  .desc {
    font-size: 1rem !important;
    font-family: "Poppins", sans-serif;
    width: 45vw;
  }
  .carouselContainer {
    width: 90%;
  }
}
@media only screen and (max-width: 767px) {
  .carousel {
    height: 300px;
  }
  .carouselContainer {
    padding: 2vh 0;
  }
  .heading {
    font-size: 1.75rem;
  }
  .desc {
    font-size: 1.125rem !important;
    width: 259px;
  }
  .startNowBtn {
    padding: 20px 20px;
  }

  /*  */
  .hero {
    width: 100%;
    margin: unset;
    display: block;
    height: 90vh;
    background-position: 40vw 0;
    background-repeat: no-repeat;
    background-size: cover;
    padding-top: 25vh;
  }
  .heroInner {
    width: 100%;
    margin-left: unset;
    margin: 0 auto;
    padding: 0 8vw;
  }
  .heroInner h1 {
    color: #fff;
    font-family: Fahkwang;
    font-size: 3.5rem;
    font-style: normal;
    font-weight: 500;
    line-height: 1;
    margin: 0;
    background-color: var(--primary-color);
    padding: 1vh 0;
  }

  .heroInner p {
    color: #fff;
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.7;
    background-color: var(--primary-color);
    padding: 1vh;
  }

  .heroCtaContainer .cta {
    padding: 1vh 8vw;
  }

  .heroCtaContainer {
    margin-top: 4vh;
  }
}
</style>
