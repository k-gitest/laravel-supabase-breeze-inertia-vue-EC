import { defineComponent, unref, withCtx, createVNode, withModifiers, withDirectives, vModelText, toDisplayString, vShow, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderStyle, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AdminEcLayout-93pFsWU1.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "CategoryRegister",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    const form = useForm({
      name: "",
      description: ""
    });
    const submit = () => {
      form.post(route("admin.category.store"), {
        onSuccess: (res) => {
          console.log("success", res);
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "CategoryRegister" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Category Register</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Category Register")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<form enctype="multipart/form-data"${_scopeId}><div class="flex flex-col items-center justify-center w-full max-w-md gap-2"${_scopeId}><div class="flex flex-col"${_scopeId}><label for="name"${_scopeId}>カテゴリ名</label><input type="text" id="name"${ssrRenderAttr("value", unref(form).name)} class="border"${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="description"${_scopeId}>カテゴリの説明</label><textarea type="text" id="description" class="border"${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea></div><div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</p><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.description)}</p></div><div style="${ssrRenderStyle(unref(props).flash ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div><div${_scopeId}><button type="submit" class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>送信</button></div></div></form>`);
          } else {
            return [
              createVNode("form", {
                onSubmit: withModifiers(submit, ["prevent"]),
                enctype: "multipart/form-data"
              }, [
                createVNode("div", { class: "flex flex-col items-center justify-center w-full max-w-md gap-2" }, [
                  createVNode("div", { class: "flex flex-col" }, [
                    createVNode("label", { for: "name" }, "カテゴリ名"),
                    withDirectives(createVNode("input", {
                      type: "text",
                      id: "name",
                      "onUpdate:modelValue": ($event) => unref(form).name = $event,
                      class: "border"
                    }, null, 8, ["onUpdate:modelValue"]), [
                      [vModelText, unref(form).name]
                    ])
                  ]),
                  createVNode("div", { class: "flex flex-col" }, [
                    createVNode("label", { for: "description" }, "カテゴリの説明"),
                    withDirectives(createVNode("textarea", {
                      type: "text",
                      id: "description",
                      "onUpdate:modelValue": ($event) => unref(form).description = $event,
                      class: "border"
                    }, null, 8, ["onUpdate:modelValue"]), [
                      [vModelText, unref(form).description]
                    ])
                  ]),
                  withDirectives(createVNode("div", null, [
                    createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.name), 1),
                    createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(form).errors.description), 1)
                  ], 512), [
                    [vShow, unref(form).errors]
                  ]),
                  withDirectives(createVNode("div", null, [
                    createVNode("p", { class: "text-sm text-red-600 dark:text-red-400" }, toDisplayString(unref(props).flash.success), 1)
                  ], 512), [
                    [vShow, unref(props).flash]
                  ]),
                  createVNode("div", null, [
                    createVNode("button", {
                      type: "submit",
                      class: "btn",
                      disabled: unref(form).processing
                    }, "送信", 8, ["disabled"])
                  ])
                ])
              ], 32)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/CategoryRegister.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
