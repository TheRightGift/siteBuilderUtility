<template>
  <div class="mt-4 bgGray" >
    <div class="container" >
      <div class="row addPtopNRemMgBtm" @click="showSocialEditor">
        <div class="col s12 m4 l3">
          <div class="brand-logo">{{ brandname }} Logo</div>
        </div>
        <div class="col s12 m4 l3">
          <h6>Contact Us</h6>
          <div class="flex">
            <a :href="social.facebook" class="btnSocial">
              <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a :href="social.twitter" class="btnSocial"
              ><i class="fa-brands fa-twitter"></i
            ></a>
            <a :href="social.instagram" class="btnSocial"
              ><i class="fa-brands fa-instagram"></i
            ></a>
            <a :href="social.youtube" class="btnSocial"
              ><i class="fa-brands fa-youtube"></i
            ></a>
            <a :href="social.tiktok" class="btnSocial"
              ><i class="fa-brands fa-tiktok"></i
            ></a>
          </div>
        </div>
        <div class="col s12 m4 l3">
          <h6>Find it fast</h6>

          <ul class="">
            <li
              v-for="category in sliceSixCate"
              :key="category.id"
              @click="showCategoryEditEditor"
            >
              <router-link :to=" loggedIn ? `#!` : { name: `product-search-category`, params: { category_name: category.name ?? `category` }, query: { additionalData: category.id ?? `category_id` } }">
                {{ category.name }}
              </router-link>
            </li>
          </ul>
        </div>
        <div class="col s12 m4 l3">
          <h6>Customer Service</h6>
          <ul class="">
            <li>
              <a :href="!isAuthenticated ? `/auth/signin` : role == `Admin` ? `/vendor/dashboard` : `/your_account/dashboard`">My Account</a>
            </li>
            <li>
              <a href="/your_account/wishlist">Wish List</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="bg-grey">
      <div class="container">
        <div class="flex gap-2">
          <p>© {{ brandname }}</p>
          - <span>Powered by Zebraline</span>
        </div>
      </div>
    </div>
    <button id="scrollToTopButton" class="scroll-to-top-button">
      <i class="material-icons">keyboard_arrow_up</i>
    </button>
  </div>
</template>
<style scoped>
.addPtopNRemMgBtm {
  padding-top: 4vh;
  margin: 0;
}
.mt-4 {
  margin-top: 4vh;
}
.logo {
  padding: 2vh 0;
}
.brand-logo {
  font-size: 1.5rem;
}
.gap-2 {
  gap: 2vh;
}
.callText {
  font-size: 0.81288rem;
  font-weight: 300;
  display: block;
}
.phone {
  font-size: 1.25038rem;
  font-weight: 400;
}
.fa-headset {
  color: var(--primary-color);
  font-size: 3rem;
}
.btnSocial {
  display: inline-block;
  font-weight: 700;
  color: #333e48;
  text-align: center;
  vertical-align: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.67rem 1rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 1.4rem;
  transition: all 0.2s ease-in-out;
  font-size: 1.25038rem;
  width: 3.125rem;
  height: 3.125rem;
  display: flex;
  justify-content: center;
  align-items: center;
}
.btnSocial i {
  font-size: 1.5rem;
}
.btnSocial:hover {
  color: #fff;
  background: #333e48;
  box-shadow: 0 4px 11px rgba(51, 62, 72, 0.35);
}
li {
  padding: 0.32rem 1.25rem;
}
a {
  font-size: 0.875rem;
  color: #333e48;
}
a:hover {
  color: #000000;
}
h6 {
  font-weight: 700;
  padding-left: 1.25rem;
}
h6:first-of-type {
  padding: 0;
  margin-bottom: 3vh;
}
.container {
  width: 85%;
}
.bgGray {
  background-color: #f8f8f8 !important;
}
.address {
  font-size: 0.875rem;
  font-weight: 400;
  font-family: "Open Sans", Helvetica, Arial, sans-serif;
}
.bg-grey {
  background-color: #eaeaea !important;
  padding: 0.5rem 0;
}
.bg-grey p {
  margin: 0;
  font-weight: 700;
}
/* Add your other CSS styles here */

/* Floating Scroll-to-Top Button */
.scroll-to-top-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: var(--primary-color);
  color: #fff; /* Button text color */
  border: none;
  border-radius: 50%;
  padding: 10px;
  cursor: pointer;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

.scroll-to-top-button i.material-icons {
  font-size: 24px;
}

@media only screen and (min-width: 768px) and (max-width: 1023px) {
  li {
    padding-left: 0;
  }
}
@media only screen and (max-width: 767px) {
  li {
    padding-left: 0;
  }
}
</style>
<script>
import templateMixin from "../../../mixin/templateMixin";
export default {
  data() {
    return {
      yourMail: "",
      social: "",
    };
  },
  mixins: [templateMixin],
};
</script>
