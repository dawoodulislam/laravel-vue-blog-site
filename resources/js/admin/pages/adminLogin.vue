<template>
	<div>
		<div class="container">
			<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
			<div
				class="
					_1adminOverveiw_table_recent
					_box_shadow
					_border_radious
					_mar_b30
					_p20
					col-md-4
				"
			>
				<div class="_logging_top">
					<h3>Logged In to Dashboard</h3>
				</div>
				<div class="space">
					<Input
						type="email"
                        v-model="data.email"
						placeholder="Email"
						style="max-width: 100%"
					/>
				</div>
				<div class="space">
					<Input
						type="password"
                        v-model="data.password"
						placeholder="********"
						style="max-width: 100%"
					/>
				</div>
				<div class="_logging_bottom">
					<Button type="primary" @click="loggin" style="width: 100%" :disabled="isLogging" :loading="isLogging">{{isLogging? 'Logging...':'Login'}}</Button>
				</div>
			</div>
		</div>
	</div>
</template>

<style scoped>
._1adminOverveiw_table_recent {
	margin: 0 auto;
	margin-top: 150px;
}
._logging_top{
    text-align: center;
    margin-bottom: 20px;
}
</style>

<script>
export default {
    data(){
        return{
            data: {
                email: "",
                password: ""
            },
            isLogging: false,
        }
    },
    methods: {
        async loggin(){
            if (this.data.email.trim() == "") {
				return this.e("Email is required");
			}
            if (this.data.password.trim() == "") {
				return this.e("Password is required");
			}
             if (this.data.password.length < 6) {
				return this.e("Password length is less than six");
			}
            this.isLogging = true
            const res = await this.callApi("post", "app/admin_login", this.data)

            if(res.status == 200){
                this.s(res.data.msg)
            }
            else{
                if(res.status == 401){
                     this.i(res.data.msg)
                }
                else if(res.status == 422){
                    for(let i in res.data.errors){
                        this.e(res.data.errors[i][0]);
                    }
                }
                else{
                    this.swr()
                }
            }
            this.isLogging = false
        }
    }
}
</script>