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
						Tags
						<Button @click="addModal = true"
							><Icon type="md-add" />Add Tag</Button
						>
					</p>

					<div class="_overflow _table_div">
						<table class="_table">
							<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Tag Name</th>
								<th>Created At</th>
								<th>Action</th>
							</tr>
							<!-- TABLE TITLE -->

							<!-- ITEMS -->
							<tr
								v-for="(tag, i) in tags"
								:key="i"
								v-if="tags.length"
							>
								<td>{{ i + 1 }}</td>
								<td class="_table_name">{{ tag.tagName }}</td>
								<td>{{ tag.created_at }}</td>
								<td>
									<Button
										type="info"
										size="small"
										@click="showEditData(tag, i)"
										>Edit</Button
									>
									<Button
										type="error"
										size="small"
										@click="showDeletingModal(tag, i)"
										:loading="tag.isDelete"
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
					title="Add Tag"
					:mask-closable="false"
					:closable="false"
				>
					<Input
						v-model="data.tagName"
						placeholder="Enter Tag Name..."
						style="max-width: 100%"
					/>

					<div slot="footer">
						<Button type="default" @click="addModal = false"
							>Close</Button
						>
						<Button
							type="primary"
							@click="addTag"
							:disabled="isAdding"
							:loading="isAdding"
							>{{ isAdding ? "Adding..." : "Add Tag" }}</Button
						>
					</div>
				</Modal>

				<!-- Edit modaal -->
				<Modal
					v-model="editModal"
					title="Edit Tag"
					:mask-closable="false"
					:closable="false"
				>
					<Input
						v-model="editData.tagName"
						placeholder="Enter Tag Name..."
						style="max-width: 100%"
					/>

					<div slot="footer">
						<Button type="default" @click="editModal = false"
							>Close</Button
						>
						<Button
							type="primary"
							@click="editTag"
							:disabled="isAdding"
							:loading="isAdding"
							>{{
								isAdding ? "Editing..." : "save Edit Tag"
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

<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";
export default {
	data() {
		return {
			data: {
				tagName: "",
			},
			addModal: false,
			isAdding: false,
			tags: [],
			editData: {
				tagName: "",
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
		async addTag() {
			if (this.data.tagName.trim() == "") {
				return this.e("Tag Name is required");
			}
			const res = await this.callApi("post", "app/create_tag", this.data);
			// console.log(res)
			if (res.status == 201) {
				// console.log(res)
				this.tags.unshift(res.data);
				this.s("Tag has been added successfuly");
				this.addModal = false;
				this.data.tagName = "";
			} else {
				if (res.status === 422) {
					if (res.data.errors.tagName) {
						this.e(res.data.errors.tagName[0]);
					}
				} else {
					this.swr();
				}
			}
		},

		async editTag() {
			if (this.editData.tagName.trim() == "") {
				return this.e("Tag Name is required");
			}
			const res = await this.callApi(
				"post",
				"app/edit_tag",
				this.editData
			);
			// console.log(res)
			if (res.status === 200) {
				this.tags[this.index].tagName = this.editData.tagName;
				// console.log(res)
				// this.tags.unshift(res.data);
				this.s("Tag has been edited successfuly");
				this.editModal = false;
			} else {
				if (res.status === 422) {
					if (res.data.errors.tagName) {
						this.e(res.data.errors.tagName[0]);
					}
				} else {
					this.swr();
				}
			}
		},
		showEditData(tag, index) {
			let obj = {
				id: tag.id,
				tagName: tag.tagName,
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
		const res = await this.callApi("get", "app/get_tags");
		if (res.status === 200) {
			this.tags = res.data;
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
                this.tags.splice(obj.deletingIndex, 1);
            }
        }
    },
};
</script>
