<template>
  <div @click="showCategoryEditEditor" class="productCatSectionInner" >
    <div class="container mt-3">
      <div class="row">
        <div v-if="categories && categories.length > 0">
          <div class="col s12 l3 m6 md-mt-3" :class="classObject(index)" v-for="(category, index) in categories.slice(0, 4)" :key="index" >
            <div class="bgGrey">
              <a href="#!">
                <div class="flex gap-2">
                  <div>
                    <img :src=" !category.image ? imageUrlWithTimestamp(seeder[index]) : imageUrlWithTimestamp(category)" class="responsive-img" />
                  </div>
                  <div>
                    <div class="dealText">
                      Catch Big
                      <div>
                        <strong>deals</strong> on the
                        <!-- <strong>{{ category.description.slice(0, 25) }}</strong> -->
                        {{ category.name }}
                      </div>
                    </div>
                    <router-link class="flex gap-1" :to="{ name: `product-search-category`, params: { category_name: category.name ?? `category` }, query: { additionalData: category.id ?? `category_id` } }">
                      <span class="shopLink">Shop now</span>
                      <span class="chevyRight"><i class="fa-solid fa-chevron-right"></i></span>
                    </router-link>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
        <div v-else>
          <div class="col s12 l3 m6 md-mt-3" :class="classObject(index)" v-for="(category, index) in seeder.slice(0, 4)" :key="index">
            <div class="bgGrey">
              <a href="">
                <div class="flex gap-2">
                  <div>
                    <img
                      :src="
                        !category.image ? seeder[index].image : category.image
                      "
                      class="responsive-img"
                    />
                  </div>
                  <div>
                    <div class="dealText">
                      Catch Big
                      <div>
                        <strong>deals</strong> on the
                        {{ category.name }}
                      </div>
                    </div>
                    <div class="flex gap-1">
                      <span class="shopLink">Shop now</span
                      ><span class="chevyRight"
                        ><i class="fa-solid fa-chevron-right"></i
                      ></span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import templateMixin from "@/mixin/templateMixin";
export default {
  mixins: [templateMixin],
  data() {
    return {
      seeder: [
        {
          name: "Camera",
          description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/190X150/img1.png",
        },
        {
          name: "TV Sets",
          description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/190X150/img2.jpg",
        },
        {
          name: "Computers",
          description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/190X150/img3.jpg",
        },
        {
          name: "Cameras",
          description:
            "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ac dictum.",
          image:
            "https://transvelo.github.io/electro-html/2.0/assets/img/190X150/img4.png",
        },
      ],
      imgDimensionWidth: 190,
      imgDimensionHeight: 150,
    };
  },
  methods: {
    classObject(index) {
      return {
        "sm-mt-3 md-mt-3": index === 1,
        "sm-mt-3": index === 2 || index === 3,
      };
    },
    imageUrlWithTimestamp(cate) {
      // Append the catetimestamp as a query parameter to the image URL
      return `${cate.image}?t=${this.catetimestamp}`;
    },
  },
};
</script>
<style scoped>
.flex {
  display: flex;
  align-items: center;
}
.bgGrey {
  background-color: #f5f5f5;
  padding: 0.5vh 1vh !important;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
.dealText {
  font-size: 1.12525rem;
  text-transform: uppercase;
  color: #333e48;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
.mt-3 {
  margin-top: 3vh;
}
.shopLink {
  color: #333e48;
  font-weight: 700;
}
.chevyRight {
  display: inline-block;
  background-color: var(--primary-color);
  width: 1.5vh;
  height: 1.5vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  border-radius: 100%;
}
.chevyRight i {
  color: #fff;
}
.gap-2 {
  gap: 2vh;
}
.gap-1 {
  gap: 1vh;
}
strong {
  font-weight: 700;
}
@media only screen and (min-width: 1024px) {
  .container {
    width: 85%;
  }
}
@media only screen and (min-width: 768px) and (max-width: 1023px) {
  .container {
    width: 90%;
  }
  .md-mt-3 {
    margin-bottom: 3vh;
  }
}
@media only screen and (max-width: 767px) {
  .sm-mt-3 {
    margin-top: 3vh;
  }
  .chevyRight {
    width: 2vh;
    height: 2vh;
  }
  .chevyRight i {
    font-size: 0.8rem;
  }
}
</style>
