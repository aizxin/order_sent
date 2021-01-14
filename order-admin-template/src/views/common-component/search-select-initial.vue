<template>
  <el-select
    v-model="initial"
    :placeholder="placeholder"
    clearable
    @change="handleChangeBrandInitial">
    <el-option
      v-for="item in list"
      :key="item"
      :label="item"
      :value="item"/>
  </el-select>
</template>

<script>

export default {
  name: 'SearchSelectInitial',
  props: {
    value: {
      type: String,
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
    }
  },
  data () {
    return {
      initial: '',
      brandInitialList: []
    }
  },
  watch: {
    value: {
      handler (val) {
        this.initial = val
      },
      immediate: true
    }
  },
  methods: {
    /*
    * 首字母选择
    * */
    handleChangeBrandInitial (val) {
      this.initial = val
      this.$emit('input', val)
    }
  }
}
</script>

<style scoped>

</style>
