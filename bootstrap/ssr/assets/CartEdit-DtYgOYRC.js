import { defineComponent, unref, withCtx, createVNode, withDirectives, toDisplayString, vShow, openBlock, createBlock, withModifiers, vModelText, createCommentVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate, ssrRenderAttr, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CartEdit",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const form = useForm({
      quantity: props.data.quantity
    });
    const submit = () => {
      form.put(route("admin.cart.update", { id: props.data.id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    const deleteCartItem = (id) => {
      form.delete(route("admin.cart.destroy", { id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CartEdit" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Cart</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Cart")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div style="${ssrRenderStyle(unref(props).flash.success ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div>`);
            if (unref(props).data) {
              _push2(`<div${_scopeId}><form${_scopeId}><ul${_scopeId}><li${_scopeId}>${ssrInterpolate(unref(props).data.id)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.product_id)}</li><li${_scopeId}><input type="number"${ssrRenderAttr("value", unref(form).quantity)} min="1" max="99"${_scopeId}></li><li${_scopeId}>${ssrInterpolate(unref(props).data.created_at)}</li><li${_scopeId}>${ssrInterpolate(unref(props).data.updated_at)}</li><li${_scopeId}>`);
              if (unref(props).data.product) {
                _push2(`<div${_scopeId}><p${_scopeId}>${ssrInterpolate(unref(props).data.product.name)}</p><p${_scopeId}>${ssrInterpolate(unref(props).data.product.description)}</p><p${_scopeId}>${ssrInterpolate(unref(props).data.product.price_excluding_tax)}</p><p${_scopeId}>${ssrInterpolate(unref(props).data.product.price_including_tax)}</p><p${_scopeId}>${ssrInterpolate(unref(props).data.product.tax_rate)}</p></div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`</li><li class="flex gap-2"${_scopeId}><button type="submit" class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>変更</button><button class="btn"${_scopeId}>削除</button></li></ul></form></div>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              withDirectives(createVNode("div", null, [
                createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
              ], 512), [
                [vShow, unref(props).flash.success]
              ]),
              unref(props).data ? (openBlock(), createBlock("div", { key: 0 }, [
                createVNode("form", {
                  onSubmit: withModifiers(submit, ["prevent"])
                }, [
                  createVNode("ul", null, [
                    createVNode("li", null, toDisplayString(unref(props).data.id), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.product_id), 1),
                    createVNode("li", null, [
                      withDirectives(createVNode("input", {
                        type: "number",
                        "onUpdate:modelValue": ($event) => unref(form).quantity = $event,
                        min: "1",
                        max: "99"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).quantity]
                      ])
                    ]),
                    createVNode("li", null, toDisplayString(unref(props).data.created_at), 1),
                    createVNode("li", null, toDisplayString(unref(props).data.updated_at), 1),
                    createVNode("li", null, [
                      unref(props).data.product ? (openBlock(), createBlock("div", { key: 0 }, [
                        createVNode("p", null, toDisplayString(unref(props).data.product.name), 1),
                        createVNode("p", null, toDisplayString(unref(props).data.product.description), 1),
                        createVNode("p", null, toDisplayString(unref(props).data.product.price_excluding_tax), 1),
                        createVNode("p", null, toDisplayString(unref(props).data.product.price_including_tax), 1),
                        createVNode("p", null, toDisplayString(unref(props).data.product.tax_rate), 1)
                      ])) : createCommentVNode("", true)
                    ]),
                    createVNode("li", { class: "flex gap-2" }, [
                      createVNode("button", {
                        type: "submit",
                        class: "btn",
                        disabled: unref(form).processing
                      }, "変更", 8, ["disabled"]),
                      createVNode("button", {
                        onClick: ($event) => deleteCartItem(unref(props).data.id),
                        class: "btn"
                      }, "削除", 8, ["onClick"])
                    ])
                  ])
                ], 32)
              ])) : createCommentVNode("", true)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/CartEdit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
