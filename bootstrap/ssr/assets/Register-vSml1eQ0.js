import { defineComponent, unref, withCtx, createVNode, withModifiers, withDirectives, vModelText, toDisplayString, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderStyle, ssrInterpolate, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Register",
  __ssrInlineRender: true,
  setup(__props) {
    const form = useForm({
      name: ""
    });
    const submit = () => {
      form.post(route("todo.store"), {
        onFinish: () => {
          form.reset("name");
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Todo Register" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Todo</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Todo")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<main class="flex flex-col items-center justify-center w-full"${_scopeId}><h1 class="text-4xl font-bold p-2"${_scopeId}>Todo Register</h1><form${_scopeId}><div class="flex flex-col items-center justify-center w-full max-w-md gap-2"${_scopeId}><div${_scopeId}><input type="text" id="name"${ssrRenderAttr("value", unref(form).name)} class="border"${_scopeId}></div><div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</p></div><div${_scopeId}><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="btn"${_scopeId}>送信</button></div></div></form></main>`);
          } else {
            return [
              createVNode("main", { class: "flex flex-col items-center justify-center w-full" }, [
                createVNode("h1", { class: "text-4xl font-bold p-2" }, "Todo Register"),
                createVNode("form", {
                  onSubmit: withModifiers(submit, ["prevent"])
                }, [
                  createVNode("div", { class: "flex flex-col items-center justify-center w-full max-w-md gap-2" }, [
                    createVNode("div", null, [
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "name",
                        "onUpdate:modelValue": ($event) => unref(form).name = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).name]
                      ])
                    ]),
                    withDirectives(createVNode("div", null, [
                      createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.name), 1)
                    ], 512), [
                      [vShow, unref(form).errors]
                    ]),
                    createVNode("div", null, [
                      createVNode("button", {
                        type: "submit",
                        disabled: unref(form).processing,
                        class: "btn"
                      }, "送信", 8, ["disabled"])
                    ])
                  ])
                ], 32)
              ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Todo/Register.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
