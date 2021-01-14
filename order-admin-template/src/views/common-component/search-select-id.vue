<template>
  <el-select
    v-model="selected"
    :placeholder="placeholder"
    clearable
    :filterable="filterable"
    remote
    :remote-method="remoteGetList"
    @change="handleChangeSelect"
  >
    <el-option
      v-for="item in list"
      :key="item.id"
      :label="item.name"
      :value="item.id"
    />
  </el-select>
</template>

<script>
export default {
  name: 'SearchSelectId',
  props: {
    value: {
      type: [String, Number],
      default: () => {
        ''
      }
    },
    placeholder: {
      type: String,
      required: true
    },
    list: {
      type: Array,
      required: true
    },
    filterable: {
      type: [Boolean, String],
      default: () => {
        return false
      }
    }
  },
  data() {
    return {
      selected: ''
    }
  },
  watch: {
    value: {
      handler(val) {
        this.selected = val
      },
      immediate: true
    }
  },
  methods: {
    /*
    * 根据条件获取列表
    * */
    remoteGetList(val) {
      this.$emit('remote', val)
    },
    /*
    * 选项选择
    * */
    handleChangeSelect(val) {
      this.$emit('input', val)
    }
  }
}
</script>

<style scoped>

</style>
