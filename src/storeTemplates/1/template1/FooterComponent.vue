<template>
    <div class="row noMarginBottom">
        <div class="col l12 footer">
            <div class="footerInner">
                <div class="col l5">
                    <h3>{{ brandname }}</h3>
                    <h4>The best look anytime, anywhere.</h4>
                </div>
                <div class="col l7">
                    <div class="col l4">
                        <h2>Men</h2>
                        <ul>
                            <li v-for="item in menItems" :key="item.id">
                                <a :href="item.link">{{ item.name }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col l4">
                        <h2>Women</h2>
                        <ul>
                            <li v-for="item in womenItems" :key="item.id">
                                <a :href="item.link">{{ item.name }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col l4">
                        <h2>Subscribe</h2>
                        <div class="row">
                            <div class="input-field col s12 noMarginTop">
                                <input
                                    v-model="email"
                                    placeholder="Your email address..."
                                    type="text"
                                />
                            </div>
                            <div class="input-field col s12 noMarginTop">
                                <a
                                    class="waves-effect waves-light btn blue darken-2"
                                    @click="subscribe"
                                    >Subscribe</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row noMarginBottom">
        <div class="container">
            <div class="col l10">
                <p>
                    <small
                        >Copyright &copy; {{ new Date().getFullYear() }} {{ brandname }}. Powered by
                        zebraline.ai.</small
                    >
                </p>
            </div>
            <div class="col l2 right-align">
                <p class="footerIconContainer">
                    <a href="#">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                    <a href="#">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="#">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>
  
  <script>
    export default {
        data() {
            return {
                menItems: [
                    { id: 1, name: "Men Jeans", link: "#" },
                    { id: 2, name: "Men Shirts", link: "#" },
                    { id: 3, name: "Men Shoes", link: "#" },
                    { id: 4, name: "Men Accessories", link: "#" },
                    { id: 5, name: "Men Jackets", link: "#" },
                ],
                womenItems: [
                    { id: 1, name: "Women Jeans", link: "#" },
                    { id: 2, name: "Tops & Shirts", link: "#" },
                    { id: 3, name: "Women Jacket", link: "#" },
                    { id: 4, name: "Heels & Flats", link: "#" },
                    { id: 5, name: "Women Accessories", link: "#" },
                ],
                email: "",
                brandname: 'StoreName'
            };
        },
        methods: {
            subscribe() {
                // Implement your subscribe logic here
                // You can access the email input value with this.email
            },
        }
    };
</script>
  
  <style scoped>
    .container {
        width: 88vw;
        max-width: unset;
    }
    .row .col.footer {
        border-top: thin solid rgb(213, 211, 211);
        border-bottom: thin solid rgb(213, 211, 211);
        padding: 10vh 0;
    }
    .noMarginTop {
        margin-top: 0 !important;
    }
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    .footerInner {
        width: 78vw;
        margin: 0 auto;
    }
    .footerInner .l5 h3 {
        margin-top: 0;
    }
    .footerInner .l5 h4 {
        font-size: 1.9rem;
    }
    .footerInner .l4 h2 {
        margin-top: 0;
        font-size: 1.7rem;
    }
    .footerInner .l4 ul li a,
    .footerIconContainer a {
        color: rgba(0, 0, 0, 0.51);
    }
    .footerInner .l4 ul li a:hover,
    .footerInner .l4 ul li a:active,
    .footerInner .l4 ul li a:visited,
    .footerIconContainer a:hover,
    .footerIconContainer a:active,
    .footerIconContainer a:visited {
        color: #0084d6;
    }
    .footerInner .l4 .input-field:first-child {
        margin-bottom: 0.5rem;
    }
    .footerIconContainer {
        display: flex;
        justify-content: space-between;
    }
</style>
  