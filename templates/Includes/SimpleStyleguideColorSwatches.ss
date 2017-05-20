<% if $ColorSwatches %>
  <h2 id="colors" class="styleguide__title">Colors</h2>
  <div class="styleguide__colors">
    <% loop $ColorSwatches %>
      <div class="styleguide__color" style="background: $CSSColor; color: $TextColor;">
        <span class="styleguide__color-text">$Name</span>
        <span class="styleguide__color-text">$Description</span>
        <code class="styleguide__color-text">$CSSColor</code>
      </div>
    <% end_loop %>
  </div>
<% end_if %>
