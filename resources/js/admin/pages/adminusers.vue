<template>
	<div>
		<div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div
					class="
						_1adminOverveiw_table_recent
						_box_shadow
						_border_radious
						_mar_b30
						_p20
					"
				>
					<p class="_title0">
						Admin Users
						<Button @click="addModal = true"
							><Icon type="md-add" />Add Admin User</Button
						>
					</p>

					<div class="_overflow _table_div">
						<table class="_table">
							<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
								<th>User Type</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
							<!-- TABLE TITLE -->

							<!-- ITEMS -->
							<tr
								v-for="(user, i) in users"
								:key="i"
								v-if="users.length"
							>
								<td>{{ i + 1 }}</td>
								<td class="_table_name">{{ user.fullName }}</td>
								<td class="">{{ user.email }}</td>
								<td class="">{{ user.userType }}</td>
								<td>{{ user.created_at }}</td>
								<td>
									<Button
										type="info"
										size="small"
										@click="showEditData(user, i)"
										>Edit</Button
									>
									<Button
										type="error"
										size="small"
										@click="showDeletingModal(user, i)"
										:loading="user.isDelete"
										>Delete</Button
									>
								</td>
							</tr>
							<!-- ITEMS -->
						</table>
					</div>
				</div>
				<!-- <Page :total="100" /> -->

				<Modal
					v-model="addModal"
					title="Add Admin User"
					:mask-closable="false"
					:closable="false"
				>
					<div class="space">
						<Input
							type="text"
							v-model="data.fullName"
							placeholder="Full Name"
							style="max-width: 100%"
						/>
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
							placeholder="Password"
							style="max-width: 100%"
						/>
					</div>
					<div class="space">
						<Select
							v-model="data.userType"
							placeholder="Select Admin User"
						>
							<Option value="Admin">Admin</Option>
							<Option value="Editor">Editor</Option>
						</Select>
					</div>

					<div slot="footer">
						<Button type="default" @click="addModal = false"
							>Close</Button
						>
						<Button
							type="primary"
							@click="addAdminUser"
							:disabled="isAdding"
							:loading="isAdding"
							>{{ isAdding ? "Adding..." : "Add Admin User" }}</Button
						>
					</div>
				</Modal>

				<!-- Edit modaal -->
				<Modal
					v-model="editModal"
					title="Edit Admin User"
					:mask-closable="false"
					:closable="false"
				>

                <div class="space">
						<Input
							type="text"
							v-model="editData.fullName"
							placeholder="Full Name"
							style="max-width: 100%"
						/>
					</div>
					<div class="space">
						<Input
							type="email"
							v-model="editData.email"
							placeholder="Email"
							style="max-width: 100%"
						/>
					</div>
					<div class="space">
						<Input
							type="password"
							v-model="editData.password"
							placeholder="Password"
							style="max-width: 100%"
						/>
					</div>
					<div class="space">
						<Select
							v-model="editData.userType"
							placeholder="Select Admin User"
						>
							<Option value="Admin">Admin</Option>
							<Option value="Editor">Editor</Option>
						</Select>
					</div>


					<div slot="footer">
						<Button type="default" @click="editModal = false"
							>Close</Button
						>
						<Button
							type="primary"
							@click="editAdminUser"
							:disabled="isAdding"
							:loading="isAdding"
							>{{
								isAdding ? "Editing..." : "Save Edited Admin User"
							}}</Button
						>
					</div>
				</Modal>

				<!-- Deleting Modal -->

				<!-- <Modal v-model="showDeleteModal" width="360">
					<p slot="header" style="color: #f60; text-align: center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align: center">
						<p>
							Are You sure to delete this modal?
						</p>
					</div>
					<div slot="footer">
						<Button
							type="error"
							size="large"
							long
							:loading="isDeleting"
							:disabled="isDeleting"
							@click="deleteTag"
							>Delete</Button
						>
					</div>
				</Modal> -->

				<deleteModal></deleteModal>
			</div>
		</div>
	</div>
</template>

<style>
    .space{
        margin: 15px 0;
    }
</style>

<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";
export default {
	data() {
		return {
			data: {
				fullName: "",
				email: "",
				password: "",
				userType: "Admin",
			},
			addModal: false,
			isAdding: false,
			users: [],
			editData: {
				fullName: "",
				email: "",
				password: "",
				userType: "",
			},
			editModal: false,
			index: -1,
			showDeleteModal: false,
			isDeleting: false,
			deleteItem: {},
			deletingIndex: -1,
		};
	},
	methods: {
		async addAdminUser() {
			if (this.data.fullName.trim() == "") {
				return this.e("Full Name is required");
			}
            if (this.data.email.trim() == "") {
				return this.e("Email is required");
			}
            if (this.data.password.trim() == "") {
				return this.e("Password is required");
			}
            if (this.data.userType.trim() == "") {
				return this.e("User Type is required");
			}
			const res = await this.callApi("post", "app/create_users", this.data);
			// console.log(res)
			if (res.status == 201) {
				// console.log(res)
				this.users.unshift(res.data);
				this.s("Admin user has been added successfuly");
				this.addModal = false;
				this.data.fullName = "";
				this.data.email = "";
				this.data.password = "";
				this.data.userType = "";
			} else {
				if (res.status === 422) {
                    for(let i in res.data.errors){
                        this.e(res.data.errors[i][0]);
                    }
					// if (res.data.errors.tagName) {
					// 	this.e(res.data.errors.tagName[0]);
					// }
				} else {
					this.swr();
				}
			}
		},

		async editAdminUser() {
			if (this.editData.fullName.trim() == "") {
				return this.e("Full Name is required");
			}
            if (this.editData.email.trim() == "") {
				return this.e("Email is required");
			}
            if (this.editData.userType.trim() == "") {
				return this.e("User Type is required");
			}
			const res = await this.callApi(
				"post",
				"app/edit_user",
				this.editData
			);
			// console.log(res)
			if (res.status === 200) {
				this.users[this.index] = this.editData;
				// console.log(res)
				// this.tags.unshift(res.data);
				this.s("User has been edited successfuly");
				this.editModal = false;
			} else {
				if (res.status === 422) {
					for(let i in res.data.errors){
                        this.e(res.data.errors[i][0]);
                    }
				} else {
					this.swr();
				}
			}
		},
		showEditData(user, index) {
			let obj = {
				id: user.id,
				fullName: user.fullName,
				email: user.email,
				userType: user.userType,
			};
			this.editData = obj;
			this.editModal = true;
			this.index = index;
		},
		async deleteTag() {
			this.isDeleting = true;
			// if (!confirm("Are you sure to delete this tag?")) return;

			// this.$set(tag, "isDelete", true);

			const res = await this.callApi(
				"post",
				"app/delete_tag",
				this.deleteItem
			);

			if (res.status === 200) {
				this.tags.splice(this.deletingIndex, 1);
				this.s("Tag has been deleted successfully");
			} else {
				this.swr();
			}
			this.isDeleting = false;
			this.showDeleteModal = false;
		},
		showDeletingModal(tag, i) {
			const deleteModalObj = {
				showDeleteModal: true,
				dataUrl: "app/delete_tag",
				data: tag,
				deletingIndex: i,
				isDeleted: false,
			};
			this.$store.commit("setDeleteModalObj", deleteModalObj);
			// this.deleteItem = tag
			// this.deletingIndex = i
			// this.showDeleteModal = true
		},
	},

	async created() {
		const res = await this.callApi("get", "app/get_users");
		if (res.status === 200) {
			this.users = res.data;
		} else {
			this.swr();
		}
	},
	components: {
		deleteModal,
	},
	computed: {
		...mapGetters(["getDeleteModalObj"]),
	},
	watch: {
		getDeleteModalObj(obj) {
			console.log(obj);
			if (obj.isDeleted) {
				this.tags.splice(obj.deletingIndex, 1);
			}
		},
	},
};
</script>
