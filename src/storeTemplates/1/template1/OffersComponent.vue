<template>
    <div class="row noMarginBottom">
      <div class="col l12 offerBackground">
        <div class="container">
          <div class="parallax-container">
            <div class="parallax"><img src="https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2019/12/banner-03.jpg"></div>
  
            <div class="row parallaxContent">
              <div class="col l6">
                <h2>Special Offer</h2>
                <p>
                  Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.
                </p>
                <h5>Get 20% Discount, Use Code OFF20</h5>
                <div class="offerCtaContainer">
                  <a href="#">Shop Now</a>
                </div>
              </div>
            </div>
  
          </div>
  
          <div class="row sellingPoints noMarginBottom">
            <div class="col l3">
              <img src="https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/globe-free-img.png" alt="" decoding="async" loading="lazy">
              <h5>Fast Shipping</h5>
              <p class="center-align">It elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
            </div>
            <div class="col l3">
              <img src="https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/quality-free-img.png" alt="" decoding="async" loading="lazy">
              <h5>Best Quality</h5>
              <p class="center-align">It elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
            </div>
            <div class="col l3">
              <img src="https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/tag-free-img.png" alt="" decoding="async" loading="lazy">
              <h5>Best Offer</h5>
              <p class="center-align">It elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
            </div>
            <div class="col l3">
              <img src="https://websitedemos.net/brandstore-02/wp-content/uploads/sites/150/2018/12/lock-free-img.png" alt="" decoding="async" loading="lazy">
              <h5>Secure Payment</h5>
              <p class="center-align">It elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    mounted() {
      // Initialize Materialize components
      // M.AutoInit();
    }
  }
  </script>
  
  <style scoped>
  html, body {
    padding: 0;
    margin: 0;
  }
  .noMarginBottom {
    margin-bottom: 0 !important;
  }
  .row .col.offerBackground {
    padding-left: 0 !important;
    padding-right: 0 !important;
    padding-top: 10vh;
    padding-bottom: 7vh;
  }
  .container {
    width: 88%;
    max-width: unset;
  }
  .offerCtaContainer a {
    padding: 1vh 2vw;
    text-transform: uppercase;
    border: thin solid#fff;
    background-color: #fff;
    color: #000;
  }
  .offerCtaContainer a:hover {
    color: #fff;
    background-color: unset;
  }
  /* Parallax */
  .parallax-container {
    height: 70vh;
    display: flex;
    align-items: center;
  }
  .row.parallaxContent {
    width: 100%;
    color: #fff;
    padding-left: 5vw;
  }
  .parallaxContent p {
    font-size: 1.1rem;
  }
  .parallaxContent h5 {
    margin-bottom: 5vh;
  }
  .row.sellingPoints{
    padding: 13vh 0 2vh;
  }
  
  .row.sellingPoints .col.l3 {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .row.sellingPoints img {
    width: 4vw;
    height: 9vh;
  }
  .row.sellingPoints p{
    font-size: 1.2rem;
  }
  </style>
  