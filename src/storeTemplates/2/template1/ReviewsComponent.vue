<template>
    <div>
        <div class="container">
            <div class="row">
                <h4 class="header">Our Clients Say</h4>
                <div class="col s12 l4 m4">
                    <div class="card py-4">
                        <img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/testimonial-thumb1.png" alt="responsive-img king client-pic" />
                        <h6 class="client-name">Nike Mardson</h6>
                        <p class="client-brand">fashion</p>
                        <p class="client-description">
                            Lorem ipsum dolor sit amet, consectetur adipiscing <br />
                            elit. Sed non nisi ante. <br />
                            Vestibulum mattis ligula a commodo semper
                        </p>
                        <div class="ratings">
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                        </div>
                    </div>
                </div>
    
                <div class="col s12 l4 m4">
                    <div class="card py-4">
                        <img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/testimonial-thumb2.png" alt=" ressponsive-img queen client-pic" />
                        <h6 class="client-name">Laura Joghnson</h6>
                        <p class="client-brand">fashion</p>
                        <p class="client-description">
                            Lorem ipsum dolor sit amet, consectetur adipiscing <br />
                            elit. Sed non nisi ante. <br />
                            Vestibulum mattis ligula a commodo semper
                        </p>
                        <div class="ratings">
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                        </div>
                    </div>
                </div>
    
                <div class="col s12 l4 m4">
                    <div class="card py-4">
                        <img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/testimonial-thumb3.png" alt="responsive-img prince client-pic" />
                        <h6 class="client-name">Richard Smith</h6>
                        <p class="client-brand">fashion</p>
                        <p class="client-description">
                            Lorem ipsum dolor sit amet, consectetur adipiscing <br />
                            elit. Sed non nisi ante. <br />
                            Vestibulum mattis ligula a commodo semper
                        </p>
                        <div class="ratings">
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                            <i class="fa-solid fa-star" style="color: var(--primary-color)"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
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

content: ' ';
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
        margin-top: 0%;;
    }

    .client-name {
        font-weight: 500;
        font-size: 1.5rem;
        font-family: "Inter", sans-serif;
        font-family: "Jost", sans-serif;
        margin: 0%;
    }

    .client-brand {
        font-family: "Inter", sans-serif;
        font-family: "Jost", sans-serif;
        color: #a9a7a7;
        font-size: 120%;
        margin: 0%;
    }

    .client-description {
        text-align: center;
        margin: 0%;
        color: #807d7d;
        font-weight: lighter;
    }
</style>
<script>
    export default {};
</script>