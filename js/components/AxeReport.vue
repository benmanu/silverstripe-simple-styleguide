<template>
  <div class="a11y-report">
    <p>Error: {{ error }}</p>
    <p>Inapplicable: {{ inapplicable.length }}</p>
    <p>Incomplete: {{ incomplete.length }}</p>
    <p>Passes: {{ passes.length }}</p>
    <p>Timestamp: {{ timestamp }}</p>
    <p>URL: {{ url }}</p>
    <p>Violations: {{ violations.length }}</p>

    <div v-for="violation in violations" :key="violation.id" class="block">
      <div class="description">
        <p><strong>{{ violation.help }} ({{ violation.id }})</strong></p>
        <p><strong>Issue description</strong></p>
        <p>{{ violation.description }}</p>
      </div>

      <div class="summary">
        <p><strong>Impact:</strong> <span class="impact" :class="`impact--${violation.impact}`">{{ violation.impact }}</span></p>
        <p><a :href="violation.helpUrl" target="_blank" rel="noopener">Learn more</a></p>
      </div>

      <v-axe-nodes :nodes="violation.nodes"></v-axe-nodes>

      <div class="tags">
        <strong>Issue tags:</strong>
        <span v-for="(tag, index) in violation.tags" :key="index" class="tag">
          {{ tag }}
        </span>
      </div>
    </div>
  </div>
</template>

<script>
  import axe from 'axe-core';
  import AxeNodes from './AxeNodes';

  export default {
    components: {
      'v-axe-nodes': AxeNodes,
    },
    props: {
      options: {
        type: Object,
        default: {
          runOnly: {
            type: 'tag',
            values: [ 'wcag2a', 'wcag2aa' ],
          },
        },
      },
    },
    data() {
      return {
        error: null,
        inapplicable: [],
        incomplete: [],
        passes: [],
        timestamp: null,
        url: null,
        violations: [],
      }
    },
    created() {
      axe.run(document, this.options, (error, results) => {
        this.error = error;
        this.inapplicable = results.inapplicable;
        this.incomplete = results.incomplete;
        this.passes = results.passes;
        this.timestamp = results.timestamp;
        this.url = results.url;
        this.violations = results.violations;
      });
    },
  };
</script>

<style scoped>
  .block {
    border: 1px solid #666;
    border-radius: 5px;
    padding: 0.5rem;
    margin: 0.5rem 0;

    display: grid;
    grid-template-columns: auto 100px;
    grid-template-rows: auto auto;
    grid-gap: 0.5rem;
  }

  .block > * {
    display: flex;
    flex-direction: column;
  }

  /* .description {} */

  /* .summary {} */

  .tags {
    grid-column: 1 / 3;

    display: flex;
    flex-direction: row;
  }

  .tags > * {
    margin-right: 0.5rem;
  }

  .impact {
    color: #31708f;
  }

  .impact--serious {
    color: #8a6d3b;
  }

  .impact--critical {
    color: #a94442;
  }

  p {
    margin: 0;
  }
</style>
