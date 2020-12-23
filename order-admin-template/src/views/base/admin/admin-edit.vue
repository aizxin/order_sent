<template>
  <el-dialog v-el-drag-dialog :title="editTitle + '管理员'" :visible.sync="dialogFormVisible">
    <el-form
      ref="editForm"
      :model="editData"
      label-position="left"
      label-width="70px"
      style="width: 80%; margin-left:50px;"
    >

      <el-form-item label="登陆名" prop="username">
        <el-input v-model="editData.username" placeholder="用户登陆名" />
      </el-form-item>

      <el-form-item label="姓名" prop="name">
        <el-input v-model="editData.name" placeholder="用户名" />
      </el-form-item>

      <el-form-item v-if="value < 1" label="密码" prop="password">
        <el-input v-model="editData.password" type="password" placeholder="密码" />
      </el-form-item>

      <el-form-item v-if="value < 1" label="确认密码" prop="confirm_password">
        <el-input v-model="editData.confirm_password" type="password" placeholder="确认密码" />
      </el-form-item>

      <el-form-item label="邮箱" prop="email">
        <el-input v-model="editData.email" placeholder="邮箱" />
      </el-form-item>

      <el-form-item label="电话" prop="mobile">
        <el-input v-model="editData.mobile" placeholder="电话" />
      </el-form-item>
      <el-form-item label="头像" prop="avatar">
        <single-image-upload v-model="editData.avatar" />
      </el-form-item>

      <el-form-item label="状态" prop="status">
        <el-radio-group v-model="editData.status">
          <el-radio-button :label="0">禁用</el-radio-button>
          <el-radio-button :label="1">启用</el-radio-button>
        </el-radio-group>

        <el-input v-model="editData.id" type="hidden" />
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button type="primary" @click="handleDataOperate($route.name)">确认</el-button>
    </div>
  </el-dialog>
</template>

<script>
import editMixin from '@/mixins/editMixin'

import SingleImageUpload from '@/components/Upload/SingleImage'

export default {
  name: 'AdminEdit',
  components: {
    SingleImageUpload
  },
  mixins: [editMixin],
  methods: {
    async handleDataInfo(params) {
      const { data } = await this.getSingleInfo(this.$route.name, params)
      this.editData = data
    }
  }
}
</script>

<style scoped>

</style>
