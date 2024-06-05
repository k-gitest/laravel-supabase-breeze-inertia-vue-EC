import { defineComponent, ref, unref, useSSRContext, computed, withCtx, createVNode, createTextVNode, openBlock, createBlock, withModifiers, withDirectives, vModelText, Fragment, renderList, toDisplayString, vModelSelect, vShow } from "vue";
import { ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderComponent, ssrIncludeBooleanAttr, ssrLooseContain, ssrLooseEqual, ssrRenderStyle } from "vue/server-renderer";
import { usePage, useForm, Head, Link } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./AdminEcLayout-93pFsWU1.js";
import { _ as _sfc_main$3 } from "./EcImageGallery-BXIFEkZy.js";
import { s as supabaseURL, a as supabaseNoImage } from "./supabase-B-jbb2wE.js";
import "./ApplicationLogo-B2173abF.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "./DropdownLink-O6UtYnah.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "AdminEcImageEditor",
  __ssrInlineRender: true,
  props: {
    images: {},
    formImage: {},
    preview: {}
  },
  emits: ["handleFileChange", "handleFileDelete"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    ref(null);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><h3>登録画像</h3>`);
      if (props.images && props.images.length) {
        _push(`<div class="grid grid-cols-3 gap-5"><!--[-->`);
        ssrRenderList(props.images, (image) => {
          _push(`<div class="indicator"><span class="indicator-item badge badge-neutral cursor-pointer">×</span><div class="avatar"><div class="w-24 rounded"><img${ssrRenderAttr("src", unref(supabaseURL) + image.path)}></div></div></div>`);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<div class="max-w-52 max-h-52"><img${ssrRenderAttr("src", unref(supabaseURL) + unref(supabaseNoImage))}></div>`);
      }
      _push(`<div class="flex flex-col"><input type="file" id="image" class="hidden" accept="image/*"><button class="btn btn-sm">画像を追加</button></div>`);
      if (props.formImage.length > 0) {
        _push(`<div class="flex gap-2"><!--[-->`);
        ssrRenderList(props.formImage, (file, index) => {
          _push(`<div${ssrRenderAttr("id", `${file.name}`)} class="max-w-32 flex flex-col justify-between items-center cursor-pointer"><img${ssrRenderAttr("src", props.preview[index].url)} class="max-w-32"><p class="cursor-pointer">${ssrInterpolate(file.name)}</p></div>`);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/AdminEcImageEditor.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "ProductEdit",
  __ssrInlineRender: true,
  setup(__props) {
    const { props } = usePage();
    let preview = [];
    const form = useForm({
      name: props.data.name,
      description: props.data.description,
      price_excluding_tax: props.data.price_excluding_tax,
      tax_rate: props.data.tax_rate,
      category_id: props.data.category_id,
      image: []
    });
    const submit = () => {
      form.post(route("admin.product.update", { id: props.data.id }), {
        headers: {
          "X-HTTP-Method-Override": "PUT"
        },
        preserveState: false,
        onSuccess: (res) => {
          console.log(res);
        }
      });
    };
    const price_including_tax = computed(() => {
      return (form.price_excluding_tax * (1 + form.tax_rate / 100)).toFixed(2);
    });
    const deleteProduct = () => {
      form.delete(route("product.destroy", { id: props.data.id }), {
        preserveState: false,
        onSuccess: (res) => {
          console.log(res);
        }
      });
    };
    const handleFileChange = (event) => {
      const target = event.target;
      const file = target.files[0];
      if (file) {
        form.image.push(file);
        const blob = URL.createObjectURL(file);
        preview.push({ name: file.name, url: blob });
        setTimeout(() => {
          URL.revokeObjectURL(blob);
        }, 2e3);
      }
    };
    const handleFileDelete = (event) => {
      const currentTarget = event.currentTarget;
      form.image = form.image.filter((file) => file.name !== currentTarget.id);
      preview = preview.filter((file) => file.name !== currentTarget.id);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: "ProductEdit" }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$2, null, {
        header: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"${_scopeId}>Product Edit</h2>`);
          } else {
            return [
              createVNode("h2", { class: "font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" }, "Product Edit")
            ];
          }
        }),
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (unref(props).data) {
              _push2(`<div${_scopeId}><form enctype="multipart/form-data" class="flex gap-2"${_scopeId}><div class="flex flex-col gap-2"${_scopeId}>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                images: unref(props).data.image
              }, null, _parent2, _scopeId));
              _push2(`</div><div class="flex flex-col items-start justify-start w-full max-w-md gap-2"${_scopeId}><div class="flex flex-col"${_scopeId}><label for="name"${_scopeId}>商品名</label><input type="text" id="name"${ssrRenderAttr("value", unref(form).name)} class="border"${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="description"${_scopeId}>商品の説明</label><textarea type="text" id="description" class="border"${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea></div><div class="flex flex-col"${_scopeId}><label for="category"${_scopeId}>カテゴリ</label><select id="category" class="select-sm border w-full max-w-xs"${_scopeId}><option disabled${ssrIncludeBooleanAttr(Array.isArray(unref(form).category_id) ? ssrLooseContain(unref(form).category_id, null) : ssrLooseEqual(unref(form).category_id, null)) ? " selected" : ""}${_scopeId}>選択してください</option><!--[-->`);
              ssrRenderList(unref(props).category.data, (category) => {
                _push2(`<option${ssrRenderAttr("value", category.id)}${ssrIncludeBooleanAttr(category.id === unref(form).category_id) ? " selected" : ""}${_scopeId}>${ssrInterpolate(category.name)}</option>`);
              });
              _push2(`<!--]--></select></div><div class="flex flex-col"${_scopeId}><label for="price_excluding_tax"${_scopeId}>税抜き価格</label><input type="text" id="price_excluding_tax"${ssrRenderAttr("value", unref(form).price_excluding_tax)} class="border"${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="price_including_tax"${_scopeId}>税込み価格</label><input type="text" id="price_including_tax"${ssrRenderAttr("value", price_including_tax.value)} class="border" disabled${_scopeId}></div><div class="flex flex-col"${_scopeId}><label for="tax_rate"${_scopeId}>税率</label><div clas="flex"${_scopeId}><input type="number" id="tax_rate"${ssrRenderAttr("value", unref(form).tax_rate)} class="border grow"${_scopeId}><span class="w-full"${_scopeId}>%</span></div></div>`);
              _push2(ssrRenderComponent(_sfc_main$1, {
                images: unref(props).data.image,
                formImage: unref(form).image,
                preview: unref(preview),
                onHandleFileChange: handleFileChange,
                onHandleFileDelete: handleFileDelete
              }, null, _parent2, _scopeId));
              _push2(`<div style="${ssrRenderStyle(unref(form).errors ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</p><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(form).errors.description)}</p></div><div style="${ssrRenderStyle(unref(props).flash ? null : { display: "none" })}"${_scopeId}><p class="text-sm text-red-600 dark:text-red-400"${_scopeId}>${ssrInterpolate(unref(props).flash.success)}</p></div><div class="flex gap-2"${_scopeId}><button type="submit" class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>編集</button>`);
              _push2(ssrRenderComponent(unref(Link), {
                href: _ctx.route("admin.stock.show", { id: unref(props).data.id }),
                class: "btn"
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(`在庫`);
                  } else {
                    return [
                      createTextVNode("在庫")
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
              _push2(`<button class="btn"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""}${_scopeId}>削除</button></div></div></form></div>`);
            } else {
              _push2(`<p${_scopeId}>カテゴリーが登録されていません</p>`);
            }
          } else {
            return [
              unref(props).data ? (openBlock(), createBlock("div", { key: 0 }, [
                createVNode("form", {
                  onSubmit: withModifiers(submit, ["prevent"]),
                  enctype: "multipart/form-data",
                  class: "flex gap-2"
                }, [
                  createVNode("div", { class: "flex flex-col gap-2" }, [
                    createVNode(_sfc_main$3, {
                      images: unref(props).data.image
                    }, null, 8, ["images"])
                  ]),
                  createVNode("div", { class: "flex flex-col items-start justify-start w-full max-w-md gap-2" }, [
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "name" }, "商品名"),
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
                      createVNode("label", { for: "description" }, "商品の説明"),
                      withDirectives(createVNode("textarea", {
                        type: "text",
                        id: "description",
                        "onUpdate:modelValue": ($event) => unref(form).description = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).description]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "category" }, "カテゴリ"),
                      withDirectives(createVNode("select", {
                        id: "category",
                        "onUpdate:modelValue": ($event) => unref(form).category_id = $event,
                        class: "select-sm border w-full max-w-xs"
                      }, [
                        createVNode("option", { disabled: "" }, "選択してください"),
                        (openBlock(true), createBlock(Fragment, null, renderList(unref(props).category.data, (category) => {
                          return openBlock(), createBlock("option", {
                            key: category.id,
                            value: category.id,
                            selected: category.id === unref(form).category_id
                          }, toDisplayString(category.name), 9, ["value", "selected"]);
                        }), 128))
                      ], 8, ["onUpdate:modelValue"]), [
                        [vModelSelect, unref(form).category_id]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "price_excluding_tax" }, "税抜き価格"),
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "price_excluding_tax",
                        "onUpdate:modelValue": ($event) => unref(form).price_excluding_tax = $event,
                        class: "border"
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, unref(form).price_excluding_tax]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "price_including_tax" }, "税込み価格"),
                      withDirectives(createVNode("input", {
                        type: "text",
                        id: "price_including_tax",
                        "onUpdate:modelValue": ($event) => price_including_tax.value = $event,
                        class: "border",
                        disabled: ""
                      }, null, 8, ["onUpdate:modelValue"]), [
                        [vModelText, price_including_tax.value]
                      ])
                    ]),
                    createVNode("div", { class: "flex flex-col" }, [
                      createVNode("label", { for: "tax_rate" }, "税率"),
                      createVNode("div", { clas: "flex" }, [
                        withDirectives(createVNode("input", {
                          type: "number",
                          id: "tax_rate",
                          "onUpdate:modelValue": ($event) => unref(form).tax_rate = $event,
                          class: "border grow"
                        }, null, 8, ["onUpdate:modelValue"]), [
                          [vModelText, unref(form).tax_rate]
                        ]),
                        createVNode("span", { class: "w-full" }, "%")
                      ])
                    ]),
                    createVNode(_sfc_main$1, {
                      images: unref(props).data.image,
                      formImage: unref(form).image,
                      preview: unref(preview),
                      onHandleFileChange: handleFileChange,
                      onHandleFileDelete: handleFileDelete
                    }, null, 8, ["images", "formImage", "preview"]),
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
                    createVNode("div", { class: "flex gap-2" }, [
                      createVNode("button", {
                        type: "submit",
                        class: "btn",
                        disabled: unref(form).processing
                      }, "編集", 8, ["disabled"]),
                      createVNode(unref(Link), {
                        href: _ctx.route("admin.stock.show", { id: unref(props).data.id }),
                        class: "btn"
                      }, {
                        default: withCtx(() => [
                          createTextVNode("在庫")
                        ]),
                        _: 1
                      }, 8, ["href"]),
                      createVNode("button", {
                        onClick: deleteProduct,
                        class: "btn",
                        disabled: unref(form).processing
                      }, "削除", 8, ["disabled"])
                    ])
                  ])
                ], 32)
              ])) : (openBlock(), createBlock("p", { key: 1 }, "カテゴリーが登録されていません"))
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/EC/Admin/ProductEdit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
