<script setup lang="ts">
import ClassDetails from '../sharedUI/classdetails.vue'
 import SidebarWithBtn from '@/components/interfaces/sidebarwithbtn.vue'
defineProps<{
  person: object
  subject: object
  package: object
  classItem: object | null
}>()
</script>

<template>


  <ClassDetails
    role="tutor"
    :person="person"
    :subject="subject"
    :package="package"
    :classItem="classItem"
  />
</template>