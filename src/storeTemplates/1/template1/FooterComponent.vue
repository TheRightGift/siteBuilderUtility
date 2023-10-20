<template>
    <div>
        <div class="row noMarginBottom">
            <div class="col l12 s12 footer">
                <div class="footerInner">
                    <div class="col l7 s12 mg-Btm-4">
                        <h3>{{ brandname }}</h3>
                        <h4>{{ brandShortsDesc }}</h4>
                    </div>
                    <div class="col l5 s12">
                        <div class="col l4 s12">
                            <h2>Discover</h2>
                            <ul>
                                <li>
                                    <a :href="loggedIn ? `#!` : `/`">Home</a>
                                </li>
                                <li
                                    v-for="category in categories"
                                    :key="category.id"
                                    @click="showCategoryEditEditor"
                                >
                                    <router-link
                                        :to="
                                            loggedIn
                                                ? `#!`
                                                : {
                                                      name: `product-search-category`,
                                                      params: {
                                                          category_name:
                                                              category.name ??
                                                              `category`,
                                                      },
                                                      query: {
                                                          additionalData:
                                                              category.id ??
                                                              `category_id`,
                                                      },
                                                  }
                                        "
                                        class="link"
                                        >{{ category.name }}</router-link
                                    >
                                </li>

                                <li>
                                    <a href="#blog">Blog</a>
                                </li>
                                <li>
                                    <a :href="mailUs">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col l7 s12">
                            <h2>Subscribe</h2>
                            <div class="row">
                                <div class="input-field col s12 noMarginTop">
                                    <input
                                        v-model="yourMail"
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
        <div class="row noMarginBottom" @click="showSocialEditor">
            <div class="container">
                <div class="col l10 s12">
                    <p class="mobileCenterText noMarginBottom">
                        <small
                            >Copyright &copy; {{ new Date().getFullYear() }}
                            {{ brandname }}. Powered by zebraline.ai.</small
                        >
                    </p>
                </div>
                <div class="col l2 s12 right-align">
                    <p class="footerIconContainer">
                        <a :href="social.facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a :href="social.youtube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <a :href="social.twitter">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a :href="social.instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a :href="social.tiktok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
    export default {
        data() {
            return {
                yourMail: "",
                social: "",
            };
        },
        methods: {
            subscribe() {
                // Implement your subscribe logic here
                // You can access the email input value with this.email
            },
            showSocialEditor() {
                this.$emit("showSocialEditor", true);
            },
        },
        props: {
            brandname: String,
            categories: Array,
            socials: Object,
            loggedIn: Boolean,
            email: String,
        },
        computed: {
            mailUs() {
                return (
                    "mailto:" +
                    this.email +
                    "?subject=Contact%20Us&body=Hello%20Team"
                );
            },
            brandShortsDesc() {
                if (this.branddesc !== "") {
                    return this.branddesc;
                } else {
                    return "Pulvinar aenean dignissim porttitor sed risus urna, pretium quis non id.";
                }
            },
        },
        watch: {
            socials(newVal) {
                this.social = { facebook: "", youtube: "", tiktok: "", twitter: "", instagram: ""};
                if (newVal !== null && newVal !== undefined) {
                    if (Object.entries(newVal).length !== 0) {
                        this.social = newVal;
                    }
                }
            },
        },
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
    .footerInner .l4 h2,
    .footerInner .l7 h2 {
        margin-top: 0;
        font-size: 1.7rem;
    }
    .footerInner .l4 ul li a,
    .footerIconContainer a,
    .footerInner ul li .link {
        color: rgba(0, 0, 0, 0.51);
        font-size: 1rem;
        cursor: pointer;
    }
    .footerInner .l4 ul li a:hover,
    .footerInner .l4 ul li a:active,
    .footerInner .l4 ul li a:visited,
    .footerIconContainer a:hover,
    .footerIconContainer a:active,
    .footerIconContainer a:visited,
    .footerInner ul li .link:hover,
    .footerInner ul li .link:active,
    .footerInner ul li .link:visited {
        color: #24262b;
        text-transform: capitalize;
        opacity: 1;
    }
    .footerInner .l4 .input-field:first-child {
        margin-bottom: 0.5rem;
    }
    .footerIconContainer {
        display: flex;
        justify-content: space-between;
    }

    /* MOBILE */
    @media only screen and (max-width: 767px) {
        .row .col.footer[data-v-abfbddf2] {
            padding: 5vh 0;
        }
        .footerIconContainer {
            justify-content: space-around;
        }

        .mobileCenterText {
            text-align: center;
        }

        .footerInner .s12 h3 {
            font-size: 2.1rem;
        }
        .footerInner .s12 h4 {
            font-size: 1.3rem;
            font-weight: 500;
        }
        .mg-Btm-4 {
            margin-bottom: 4vh;
        }
    }
</style>
  