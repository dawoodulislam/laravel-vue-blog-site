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
						Categories
						<Button @click="addModal = true"
							><Icon type="md-add" />Add Category</Button
						>
					</p>

					<div class="_overflow _table_div">
						<table class="_table">
							<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Category Name</th>
								<th>Icon Image</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
							<!-- TABLE TITLE -->

							<!-- ITEMS -->
							<tr
								v-for="(category, i) in categories"
								:key="i"
								v-if="categories.length"
							>
								<td>{{ i + 1 }}</td>
								<td class="_table_name">
									{{ category.categoryName }}
								</td>
								<td style="width: 40px; height: 30px">
									<img :src="category.iconImage" />
								</td>
								<td>{{ category.created_at }}</td>
								<td>
									<Button
										type="info"
										size="small"
										@click="showEditData(category, i)"
										>Edit</Button
									>
									<Button
										type="error"
										size="small"
										@click="showDeletingModal(category, i)"
										:loading="category.isDelete"
										>Delete</Button
									>
								</td>
							</tr>
							<!-- ITEMS -->
						</table>
					</div>
				</div>
				<!-- <Page :total="100" /> -->

				<!-- Add Category -->
				<Modal
					v-model="addModal"
					title="Add Category"
					:mask-closable="false"
					:closable="false"
				>
					<Input
						v-model="data.categoryName"
						placeholder="Enter Category Name..."
						style="max-width: 100%"
					/>

					<Upload
						type="drag"
						ref="uploads"
						:headers="{
							'x-csrf-token': token,
							'X-Requested-With': 'XMLHttpRequest',
						}"
						:on-success="handleSuccess"
						:on-error="handleError"
						:format="['jpg', 'jpeg', 'png']"
						:max-size="2048"
						:on-format-error="handleFormatError"
						:on-exceeded-size="handleMaxSize"
						action="/app/upload"
					>
						<div style="padding: 20px 0">
							<Icon
								type="ios-cloud-upload"
								size="52"
								style="color: #3399ff"
							></Icon>
							<p>Click or drag files here to upload</p>
						</div>
					</Upload>

					<div class="demo-upload-list" v-if="data.iconImage">
						<img :src="`${data.iconImage}`" />
						<div class="demo-upload-list-cover">
							<Icon
								type="ios-trash-outline"
								@click="deleteImage"
							></Icon>
						</div>
					</div>

					<!-- <div class="image_thumb" v-if="data.iconImage">
                        <img :src ="`/admin/category/${data.iconImage}`">
                    </div> -->

					<div slot="footer">
						<Button type="default" @click="addModal = false"
							>Close</Button
						>
						<Button
							type="primary"
							@click="addCategory"
							:disabled="isAdding"
							:loading="isAdding"
							>{{
								isAdding ? "Adding..." : "Add Category"
							}}</Button
						>
					</div>
				</Modal>

				<!-- Edit modaal -->
				<Modal
					v-model="editModal"
					title="Edit Category"
					:mask-closable="false"
					:closable="false"
				>
					<Input
						v-model="editData.categoryName"
						placeholder="Enter Category Name..."
						style="max-width: 100%"
					/>

					<Upload
						v-show="isIconImageNew"
						type="drag"
						ref="uploadsEditImage"
						:headers="{
							'x-csrf-token': token,
							'X-Requested-With': 'XMLHttpRequest',
						}"
						:on-success="handleSuccess"
						:on-error="handleError"
						:format="['jpg', 'jpeg', 'png']"
						:max-size="2048"
						:on-format-error="handleFormatError"
						:on-exceeded-size="handleMaxSize"
						action="/app/upload"
					>
						<div style="padding: 20px 0">
							<Icon
								type="ios-cloud-upload"
								size="52"
								style="color: #3399ff"
							></Icon>
							<p>Click or drag files here to upload</p>
						</div>
					</Upload>

					<div class="demo-upload-list" v-if="editData.iconImage">
						<img :src="`${editData.iconImage}`" />
						<div class="demo-upload-list-cover">
							<Icon
								type="ios-trash-outline"
								@click="deleteImage(false)"
							></Icon>
						</div>
					</div>

					<!-- <div class="image_thumb" v-if="data.iconImage">
                        <img :src ="`/admin/category/${data.iconImage}`">
                    </div> -->

					<div slot="footer">
						<Button type="default" @click="closeEditModal"
							>Close</Button
						>
						<Button
							type="primary"
							@click="editCategory"
							:disabled="isAdding"
							:loading="isAdding"
							>{{
								isAdding ? "Editing..." : "Edit Category"
							}}</Button
						>
					</div>
				</Modal>

				<!-- Deleting Modal

				<Modal v-model="showDeleteModal" width="360">
					<p slot="header" style="color: #f60; text-align: center">
						<Icon type="ios-information-circle"></Icon>
						<span>Delete confirmation</span>
					</p>
					<div style="text-align: center">
						<p>Are You sure to delete this modal?</p>
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
.demo-upload-list {
	display: inline-block;
	width: 60px;
	height: 60px;
	text-align: center;
	line-height: 60px;
	border: 1px solid transparent;
	border-radius: 4px;
	overflow: hidden;
	background: #fff;
	position: relative;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
	margin-right: 4px;
}
.demo-upload-list img {
	width: 100%;
	height: 100%;
}
.demo-upload-list-cover {
	display: none;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: rgba(0, 0, 0, 0.6);
}
.demo-upload-list:hover .demo-upload-list-cover {
	display: block;
}
.demo-upload-list-cover i {
	color: #fff;
	font-size: 20px;
	cursor: pointer;
	margin: 0 2px;
}
</style>

<script>
import deleteModal from "../components/deleteModal.vue";
import {mapGetters} from 'vuex';
export default {
	data() {
		return {
			data: {
				iconImage: "",
				categoryName: "",
			},
			addModal: false,
			isAdding: false,
			categories: [],
			editData: {
				iconImage: "",
				categoryName: "",
			},
			editModal: false,
			index: -1,
			showDeleteModal: false,
			isDeleting: false,
			deleteItem: {},
			deletingIndex: -1,
			token: "",
			isIconImageNew: false,
			isEditItem: false,
		};
	},
	methods: {
		async addCategory() {
			if (this.data.categoryName.trim() == "") {
				return this.e("Category Name is required");
			}
			if (this.data.iconImage.trim() == "") {
				return this.e("Category iconImage is required");
			}
			this.data.iconImage = `${this.data.iconImage}`;
			const res = await this.callApi(
				"post",
				"app/create_category",
				this.data
			);
			// console.log(res)
			if (res.status == 201) {
				// console.log(res)
				this.categories.unshift(res.data);
				this.s("Category has been added successfuly");
				this.addModal = false;
				this.data.categoryName = "";
				this.data.iconImage = "";
				this.$refs.uploads.clearFiles();
			} else {
				if (res.status === 422) {
					if (res.data.errors.categoryName) {
						this.e(res.data.errors.categoryName[0]);
					}
					if (res.data.errors.iconImage) {
						this.e(res.data.errors.iconImage[0]);
					}
				} else {
					this.swr();
				}
			}
		},

		async editCategory() {
			if (this.editData.categoryName.trim() == "") {
				return this.e("Category Name is required");
			}
			if (this.editData.iconImage.trim() == "") {
				return this.e("Category iconImage is required");
			}
			const res = await this.callApi(
				"post",
				"app/edit_category",
				this.editData
			);
			// console.log(res)
			if (res.status === 200) {
				this.categories[this.index].categoryName =
					this.editData.categoryName;
				// console.log(res)
				// this.tags.unshift(res.data);
				this.s("Category has been edited successfuly");
				this.editModal = false;
			} else {
				if (res.status === 422) {
					if (res.data.errors.categoryName) {
						this.e(res.data.errors.categoryName[0]);
					}
					if (res.data.errors.iconImage) {
						this.e(res.data.errors.iconImage[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		showEditData(category, index) {
			// let obj = {
			// 	id: tag.id,
			// 	tagName: tag.tagName,
			// };
			this.editData = category;
			this.editModal = true;
			this.index = index;
			this.isEditItem = true;
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
				dataUrl: "app/delete_category",
				data: tag,
				deletingIndex: i,
                isDeleted: false
			};
            this.$store.commit("setDeleteModalObj", deleteModalObj);
			// this.deleteItem = tag;
			// this.deletingIndex = i;
			// this.showDeleteModal = true;
		},
		handleSuccess(res, file) {
			res = `/admin/category/${res}`;
			if (this.isEditItem) {
				return (this.editData.iconImage = res);
			}
			this.data.iconImage = res;
		},
		handleError(res, file) {
			this.$Notice.warning({
				title: "The file format is incorrect",
				desc: `${
					file.errors.file.length
						? file.errors.file[0]
						: "Something went wrong"
				}`,
			});
		},
		handleFormatError(file) {
			this.$Notice.warning({
				title: "The file format is incorrect",
				desc:
					"File format of " +
					file.name +
					" is incorrect, please select jpg or png or jpwg.",
			});
		},
		handleMaxSize(file) {
			this.$Notice.warning({
				title: "Exceeding file size limit",
				desc: "File  " + file.name + " is too large, no more than 2M.",
			});
		},
		async deleteImage(isAdd = true) {
			let image;
			console.log("hi");
			console.log(this.data.iconImage);
			if (!isAdd) {
				this.isIconImageNew = true;
				image = this.editData.iconImage;
				this.editData.iconImage = "";
				this.$refs.uploadsEditImage.clearFiles();

				console.log(isAdd);
			} else {
				image = this.data.iconImage;
				this.data.iconImage = "";
				this.$refs.uploads.clearFiles();
			}
			// console.log(this.data.iconImage)

			const res = await this.callApi("post", "app/delete_image", {
				fileName: image,
			});

			if (res.status != 200) {
				this.data.iconImage = image;
				this.swr();
			}
		},
		closeEditModal() {
			if (this.editData.categoryName.trim() == "") {
				return this.e("Category Name is required");
			}
			if (this.editData.iconImage.trim() == "") {
				return this.e("Category iconImage is required");
			}
			this.isEditItem = false;
			this.editModal = false;
		},
	},

	async created() {
		this.token = window.Laravel.csrfToken;
		const res = await this.callApi("get", "app/get_categories");
		if (res.status === 200) {
			this.categories = res.data;
		} else {
			this.swr();
		}
	},
	components: {
		deleteModal,
	},
	computed: {
		...mapGetters(["getDeleteModalObj"])
	},
	watch: {
        getDeleteModalObj(obj){
            console.log(obj);
            if(obj.isDeleted){
                this.categories.splice(obj.deletingIndex, 1+1);
            }
        }
    },
};
</script>
