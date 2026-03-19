import VicInquiryModalPlugin from "./vic-inquiry-modal/vic-inquiry-modal.plugin";

const PluginManager = window.PluginManager;
PluginManager.register(
  "VicInquiryModal",
  VicInquiryModalPlugin,
  "[data-vic-inquiry-modal]",
);
