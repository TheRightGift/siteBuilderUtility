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
                        <div class="col l4 s6">
                            <ul>
                                <li>
                                    <a :href="loggedIn ? `#!` : `/`">Home</a>
                                </li>
                                <!-- <span v-show="categories.length < 3"> -->
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
                                        >{{ category.name }}</router-link
                                    >
                                </li>
                                <!-- </span> -->

                                <li>
                                    <a href="#">Blog</a>
                                </li>
                                <li>
                                    <a :href="mailUs">Contact Us</a>
                                </li>
                            </ul>
                            <!-- <h2>{{ category.name }}</h2>
                            <ul v-if="category.subcategories.length > 0">
                                <li
                                    v-for="item in category.subcategories"
                                    :key="item.id"
                                >
                                    <a :href="item.link">{{ item.name }}</a>
                                </li>
                            </ul>
                            <ul v-else>
                                <li>
                                    <router-link
                                        :to="{
                                            name: 'product-search-category',
                                            params: {
                                                category_name:
                                                    category.name ?? 'category',
                                            },
                                            query: {
                                                additionalData:
                                                    category.id ?? 'category_id',
                                            },
                                        }"
                                        >{{ category.name }}</router-link
                                    >
                                </li>
                            </ul> -->
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
                        <a :href="socials.facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a :href="socials.youtube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <a :href="socials.twitter">
                            <i class="fa-brands fa-twitter"></i>
                        </a>
                        <a :href="socials.instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a :href="socials.tiktok">
                            <i class="fa-brands fa-twitter"></i>
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
                seeder: [
                    {
                        id: 1,
                        name: "Men",
                        link: "#",
                        subcategories: [
                            {
                                id: 1,
                                name: "Men Jeans",
                                link: "#",
                            },
                            { id: 2, name: "Men Shirts", link: "#" },
                            { id: 3, name: "Men Shoes", link: "#" },
                            { id: 4, name: "Men Accessories", link: "#" },
                            { id: 5, name: "Men Jackets", link: "#" },
                        ],
                    },
                    {
                        id: 1,
                        name: "Women",
                        link: "#",
                        subcategories: [
                            { id: 1, name: "Women Jeans", link: "#" },
                            { id: 2, name: "Tops & Shirts", link: "#" },
                            { id: 3, name: "Women Jacket", link: "#" },
                            { id: 4, name: "Heels & Flats", link: "#" },
                            { id: 5, name: "Women Accessories", link: "#" },
                        ],
                    },
                ],
                yourMail: "",
                womenItems: [],
                brandShortsDesc:
                    this.branddesc ?? "The best look anytime, anywhere.",
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
            loggedIn: Boolean,
            socials: Object,
            branddesc: String,
            email: String,
        },
        watch: {
            categories(newVal) {
                if (newVal.length > 0) {
                    newVal.forEach((category, i) => {
                        if (i < this.seeder.length) {
                            // Update existing seeder items
                            this.seeder[i].name = category.name;
                            this.seeder[i].image =
                                category.image || this.seeder[i].image;
                            this.seeder[i].description =
                                category.description || this.seeder[i].description;
                            this.seeder[i].subcategories =
                                category.subcategories ||
                                this.seeder[i].subcategories;
                        } else {
                            // Create new seeder items if there are more categories
                            this.seeder.push({
                                name: category.name,
                                image: category.image || null,
                                description: category.description || null,
                                subcategories: category.subcategories,
                            });
                        }
                    });
                }
            },
            branddesc(newVal) {
                this.brandShortsDesc = newVal;
            },
        },
        computed: {
            mailUs() {
                return (
                    `mailto:` +
                    this.email +
                    `?subject=Contact%20Us&body=Hello%20Team`
                );
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
    .footerInner .l4 h2 {
        margin-top: 0;
        font-size: 1.7rem;
    }
    .footerInner .l4 ul li a,
    .footerIconContainer a {
        color: rgba(0, 0, 0, 0.51);
        font-size: 1.2rem;
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
  