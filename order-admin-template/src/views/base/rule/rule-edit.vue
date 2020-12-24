<template>
  <div>
    <el-dialog v-el-drag-dialog :title="editTitle + '权限节点'" :visible.sync="dialogFormVisible">
      <el-form
        ref="editForm"
        :model="editData"
        label-position="left"
        label-width="70px"
        style="width: 80%; margin-left:50px;"
      >
        <el-form-item label="顶级分类" prop="pid">
          <el-cascader
            v-model="editData.pid"
            :props="{ checkStrictly: true }"
            :options="items"
            clearable
            @change="handleChange"
          />
        </el-form-item>
        <el-form-item label="接口路由" prop="route">
          <el-input v-model="editData.route" placeholder="接口路由" />
        </el-form-item>
        <el-form-item label="接口名称" prop="title">
          <el-input v-model="editData.title" placeholder="接口名称" />
        </el-form-item>
        <el-form-item label="排序" prop="title">
          <el-input v-model="editData.sort" placeholder="排序" with="70%" />
        </el-form-item>
        <el-form-item label="状态" prop="status">
          <el-radio-group v-model="editData.type">
            <el-radio-button :label="1">菜单</el-radio-button>
            <el-radio-button :label="2">按钮</el-radio-button>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="editData.type ==2" label="接口行为" prop="action">
          <el-input v-model="editData.action" placeholder="操作节点行为" />
        </el-form-item>
        <el-form-item v-if="editData.type ==1" label="路由icon" prop="icon">
          <el-input v-model="editData.icon" placeholder="接口icon" @input="selectIcon" />
        </el-form-item>
        <el-form-item v-if="editData.type ==1" label="路由URL" prop="path">
          <el-input v-model="editData.path" placeholder="路由URL" />
        </el-form-item>
        <el-form-item v-if="editData.type ==1" label="路由名称" prop="name">
          <el-input v-model="editData.name" placeholder="路由名称" />
        </el-form-item>
        <el-form-item v-if="editData.type ==1" label="路由组件" prop="component">
          <el-input v-model="editData.component" placeholder="路由组件" />
        </el-form-item>
        <el-form-item v-if="editData.type ==1" label="跳转地址" prop="redirect">
          <el-input v-model="editData.redirect" placeholder="需要重定向的地址" />
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
    <el-dialog title="选择菜单图标" :visible.sync="iconVisable" width="70%">
      <icons @selectIcon="handleSelectIcon" />
    </el-dialog>
  </div>
</template>

<script>
import editMixin from '@/mixins/editMixin'
import { searchToTree, treeToList } from '@/utils/tree-data'
import icons from '@/components/Icons/index'

export default {
  components: {
    icons
  },
  mixins: [editMixin],
  props: {
    items: {
      required: true,
      type: [Array]
    }
  },
  data() {
    return {
      iconVisable: false
    }
  },
  methods: {
    async handleDataInfo(params) {
      const { data } = await this.getSingleInfo(this.$route.name, params)
      this.editData = data
      if (data.pid !== 0) {
        this.editData.pid = searchToTree(treeToList(this.items), data.pid)
      }
    },
    handleChange(item) {
      if (!item) return
      this.editData.pid = item[item.length - 1]
    },
    // 选择菜单图标
    handleSelectIcon(item) {
      this.editData.icon = item
      this.iconVisable = false
    },
    selectIcon() {
      this.iconVisable = true
    }
  }
}
</script>

<style scoped>

</style>
