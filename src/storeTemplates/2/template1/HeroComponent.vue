<template>
  <div @click="showHeroEditor">
    <div class="carousel carousel-slider" id="promo">
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
                <router-link
                  :to="
                    loggedIn
                      ? `#!`
                      : {
                          name: `product-search-category`,
                          params: {
                            category_name:
                              heroSeeder.type == `welcome`
                                ? `all`
                                : heroSeeder.title ?? `offer`,
                          },
                          query: {
                            additionalOfferData:
                              heroSeeder.type == `welcome`
                                ? `all`
                                : heroSeeder.id ?? `offer_id`,
                          },
                        }
                  "
                  ><span> Start Buying</span></router-link
                >
              </button>
            </div>
            <div class="">
              <img :src="imageUrlWithTimestamp" class="responsive-img" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  computed: {
    imageUrlWithTimestamp() {
      // Append the timestamp as a query parameter to the image URL
      return `${this.heroSeeder[this.index].image}?t=${this.timestamp}`;
    },
  },
  data() {
    return {
      index: 0,
      heroSeeder: [
        {
          description: "The new Standard",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/416X420/img1.png",
          others: "Under favorable smartwatches",
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
    this.initCarousel();
    M.AutoInit();
  },
  methods: {
    initCarousel() {
      let elem = document.getElementById("promo");
      let instance = M.Carousel.init(elem, {
        fullWidth: true,
        indicators: true,
      });

      // Autoplay functionality
      let autoplayInterval = 4500; // Delay between slide transitions in milliseconds
      let autoplayInstance = setInterval(() => {
        instance.next();
      }, autoplayInterval);

      // Pause autoplay on mouseover and resume on mouseout
      elem.addEventListener("mouseenter", () => {
        clearInterval(autoplayInstance);
      });

      elem.addEventListener("mouseleave", () => {
        autoplayInstance = setInterval(() => {
          instance.next();
        }, autoplayInterval);
      });

      window.next = function () {
        var el = document.querySelector(".carousel");
        var l = M.Carousel.getInstance(el);
        l.next(1);
      };

      //carousel previous function
      window.prev = function () {
        var el = document.querySelector(".carousel");
        var l = M.Carousel.getInstance(el);
        l.prev(1);
      };
    },
    showHeroEditor() {
      if (this.loggedIn) {
        this.$emit("showHeroEditor", {
          evt: true,
          width: this.imgDimensionWidth,
          height: this.imgDimensionHeight,
        });
      }
    },
  },
  props: {
    loggedIn: Boolean,
    hero: Array,
    timestamp: Number,
  },
  watch: {
    hero(newVal, oldVal) {
      if (Object.entries(newVal).length > 0) {
        this.heroSeeder = newVal;
      }
      this.initCarousel();
      M.AutoInit();
    },
  },
};
</script>
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
.heading,
.desc {
  color: #333e48;
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
@media only screen and (min-width: 1024px) {
  .carouselContainer {
    width: 50%;
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
}
</style>
