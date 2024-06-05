import { defineComponent, unref, withCtx, createVNode, withModifiers, withDirectives, vModelText, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr } from "vue/server-renderer";
import { usePage, useForm, Head } from "@inertiajs/vue3";
import { _ as _sfc_main$1 } from "./AuthenticatedLayout-CquFiEPb.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Edit",
  __ssrInlineRender: true,
  setup(__props) {
    const { props: { todoList: todo } } = usePage();
    const form = useForm({
      name: todo.name
    });
    const submit = () => {
      form.put(route("todo.update", { id: todo.id }), {
        onFinish: () => {
          form.reset("name");
        }
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "Todo Edit" }, null, _parent));
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
            _push2(`<main class="flex flex-col justify-center items-center w-full"${_scopeId}><h1 class="text-4xl font-bold p-2"${_scopeId}>Todo Edit</h1><div${_scopeId}><form${_scopeId}><div class="flex flex-col items-center justify-center w-full max-w-md gap-2"${_scopeId}><div${_scopeId}><input type="text" id="name"${ssrRenderAttr("value", unref(form).name)} class="border"${_scopeId}></div><div${_scopeId}><button type="submit" class="btn"${_scopeId}>送信</button></div></div></form></div></main>`);
          } else {
            return [
              createVNode("main", { class: "flex flex-col justify-center items-center w-full" }, [
                createVNode("h1", { class: "text-4xl font-bold p-2" }, "Todo Edit"),
                createVNode("div", null, [
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
                      createVNode("div", null, [
                        createVNode("button", {
                          type: "submit",
                          class: "btn"
                        }, "送信")
                      ])
                    ])
                  ], 32)
                ])
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Todo/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
