import { defineComponent, unref, withCtx, createTextVNode, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderComponent } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartTableBody",
  __ssrInlineRender: true,
  props: {
    carts: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<tbody${ssrRenderAttrs(_attrs)}>`);
      if (_ctx.carts.length) {
        _push(`<!--[-->`);
        ssrRenderList(_ctx.carts, (cart) => {
          _push(`<!--[-->`);
          if (cart.product) {
            _push(`<tr><td><div class="flex items-center gap-3"><div class="avatar"><div class="mask mask-squircle w-12 h-12">`);
            if (cart.product.image && cart.product.image.length) {
              _push(`<div><img${ssrRenderAttr("src", unref(supabaseURL) + cart.product.image[0].path)}></div>`);
            } else {
              _push(`<div><img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))}></div>`);
            }
            _push(`</div></div><div><div class="font-bold">${ssrInterpolate(cart.product.name)}</div><div class="text-sm opacity-50">${ssrInterpolate(cart.product.description)}</div></div></div></td><td>${ssrInterpolate(cart.quantity)}</td><td>${ssrInterpolate(cart.product.price_excluding_tax)} <br><span class="badge badge-ghost badge-sm">${ssrInterpolate(cart.product.price_including_tax)}</span></td><th class="flex gap-2">`);
            _push(ssrRenderComponent(unref(Link), {
              class: "btn btn-sm",
              href: _ctx.route("cart.edit", { id: cart.id })
            }, {
              default: withCtx((_, _push2, _parent2, _scopeId) => {
                if (_push2) {
                  _push2(`編集`);
                } else {
                  return [
                    createTextVNode("編集")
                  ];
                }
              }),
              _: 2
            }, _parent));
            _push(`<button class="btn btn-ghost btn-sm">削除</button></th></tr>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<!--]-->`);
        });
        _push(`<!--]-->`);
      } else {
        _push(`<!--[--> カートには何も入っていません <!--]-->`);
      }
      _push(`</tbody>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/CartTableBody.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
