<template>
  <div class="nodes" v-if="nodes.length">
    <div class="controls" v-if="nodes.length > 1">
      <button v-on:click="active -= 1" :disabled="isPreviousDisabled">&#9664;</button>
      <button v-on:click="active += 1" :disabled="isNextDisabled">&#9654;</button>
      ({{ active + 1 }} of {{ nodes.length }})
    </div>

    <div class="node">
      <p><strong>Element location</strong></p>
      <p>{{ activeNode.target[0] }}</p>
      <p><strong>Element source</strong></p>
      <p>{{ activeNode.html }}</p>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      nodes: {
        type: Array,
        default: [],
      },
    },
    data() {
      return {
        active: 0,
      };
    },
    computed: {
      activeNode: function () {
        return this.nodes[this.active];
      },
      isPreviousDisabled: function () {
        return this.active === 0;
      },
      isNextDisabled: function () {
        return this.nodes.length - 1 === this.active;
      },
    },
  };
</script>

<style scoped>
  .nodes {
    grid-column: 1 / 3;

    border-top: 1px solid #666;
    border-bottom: 1px solid #666;
  }

  .controls {
    display: flex;
    align-items: center;
  }

  p {
    margin: 0;
  }
</style>
