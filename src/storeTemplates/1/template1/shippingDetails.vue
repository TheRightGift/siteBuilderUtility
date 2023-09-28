<template>
    <div class="row" id="sellingPoint">
		<div class="container">
			<div class="card">
				<div class="card-content">
					<div class="row noMarginBottom">
						<div class="col l3 m6 s12">
							<div class="img">
								<img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping1.png" alt="" loading="lazy">
							</div>
							<div class="detail">
								<h4 class="noMarginTop">Shipping</h4>
								<span class="grey-text">From handpicked sellers</span>
							</div>
						</div>

						<div class="col l3 m6 s12">
							<div class="img">
								<img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping2.png" alt="" loading="lazy">
							</div>
							<div class="detail">
								<h4 class="noMarginTop">Payment</h4>
								<span class="grey-text">From handpicked sellers</span>
							</div>
						</div>

						<div class="col l3 m6 s12">
							<div class="img">
								<img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping3.png" alt="" loading="lazy">
							</div>
							<div class="detail">
								<h4 class="noMarginTop">Return</h4>
								<span class="grey-text">From handpicked sellers</span>
							</div>
						</div>

						<div class="col l3 m6 s12">
							<div class="img">
								<img src="https://risingtheme.com/html/demo-suruchi-v1/suruchi/assets/img/other/shipping4.png" alt="" loading="lazy">
							</div>
							<div class="detail">
								<h4 class="noMarginTop">Support</h4>
								<span class="grey-text">From handpicked sellers</span>
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
        return {};
    },
    mounted() {},
    methods: {}
};
</script>

<style scoped>
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
</style>
