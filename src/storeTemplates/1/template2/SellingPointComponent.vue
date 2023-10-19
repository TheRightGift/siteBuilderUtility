<template>
    <div class="row" id="sellingPoint">
        <div class="container">
            <div class="card"  @click="showSellingPointEditor">
                <div class="card-content">
                    <div class="row noMarginBottom">
                        <div class="col l4 m6 s12 mobilePoint" v-for="(sellPoint, index) in sellPoint" :key="index">
                            <div class="img">
                                <img
                                    :src="sellPoint.image_or_icon"
                                    alt=""
                                    loading="lazy"
                                />
                            </div>
                            <div class="detail">
                                <h4 class="noMarginTop">{{ sellPoint.title }}</h4>
                                <span class="grey-text"
                                    >{{ sellPoint.description }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sellingPSeeder: [
					{
                        title: "Free Shipping",
                        image_or_icon:
                            "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping1.png",
                        description: "From handpicked sellers",
                    },
                    {
                        title: "Support",
                        image_or_icon:
                            "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping2.png",
                        description: "From handpicked sellers",
                    },
                    {
                        title: "60 days Return",
                        image_or_icon:
                            "https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping3.png",
                        description: "From handpicked sellers",
                    },
                    
                ],
            };
        },
        mounted() {
        },
        methods: {
            showSellingPointEditor() {
                if (this.loggedIn) {
                    this.$emit(`showSellingPointEditor`, true);
                }
            }
        },
        props: {
            loggedIn: Boolean,
            sellPoint: Array,
        },
    };
</script>

<style scoped>
    .container {
        width: 80%;
        max-width: unset;
    }
    #sellingPoint {
        padding: 5vh 0;
    }
    .noMarginTop {
        margin-top: 0 !important;
    }
    .noMarginBottom {
        margin-bottom: 0 !important;
    }
    /* Add your custom styles here */

    #sellingPoint .card-content .col {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    #sellingPoint .card-content .col .img {
        width: 37%;
        display: flex;
        justify-content: flex-end;
    }

    #sellingPoint .card-content .col .detail {
        width: 58%;
    }
    #sellingPoint .card-content .col .detail h4 {
        font-size: 1.75rem;
        margin-bottom: 0.5vh;
    }

    /* MOBILE */
    @media only screen and (max-width: 767px) {
        #sellingPoint .card-content .col .img {
            width: 40%;
            display: flex;
            justify-content: flex-start;
        }
        #sellingPoint .card-content .col .detail {
            width: 60%;
        }

        .mobilePoint {
            margin-bottom: 3vh;
        }
    }
</style>
