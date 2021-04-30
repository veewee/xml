<?php declare(strict_types=1);

require_once __DIR__.'/Xml/Dom/Builder/attribute.php';
require_once __DIR__.'/Xml/Dom/Builder/attributes.php';
require_once __DIR__.'/Xml/Dom/Builder/children.php';
require_once __DIR__.'/Xml/Dom/Builder/element.php';
require_once __DIR__.'/Xml/Dom/Builder/escaped_value.php';
require_once __DIR__.'/Xml/Dom/Builder/namespaced_attribute.php';
require_once __DIR__.'/Xml/Dom/Builder/namespaced_attributes.php';
require_once __DIR__.'/Xml/Dom/Builder/namespaced_element.php';
require_once __DIR__.'/Xml/Dom/Builder/nodes.php';
require_once __DIR__.'/Xml/Dom/Builder/value.php';
require_once __DIR__.'/Xml/Dom/Configurator/canonicalize.php';
require_once __DIR__.'/Xml/Dom/Configurator/loader.php';
require_once __DIR__.'/Xml/Dom/Configurator/normalize.php';
require_once __DIR__.'/Xml/Dom/Configurator/optimize_namespaces.php';
require_once __DIR__.'/Xml/Dom/Configurator/pretty_print.php';
require_once __DIR__.'/Xml/Dom/Configurator/traverse.php';
require_once __DIR__.'/Xml/Dom/Configurator/trim_spaces.php';
require_once __DIR__.'/Xml/Dom/Configurator/utf8.php';
require_once __DIR__.'/Xml/Dom/Configurator/validator.php';
require_once __DIR__.'/Xml/Dom/Loader/load.php';
require_once __DIR__.'/Xml/Dom/Loader/xml_file_loader.php';
require_once __DIR__.'/Xml/Dom/Loader/xml_node_loader.php';
require_once __DIR__.'/Xml/Dom/Loader/xml_string_loader.php';
require_once __DIR__.'/Xml/Dom/Locator/Attributes/attributes_list.php';
require_once __DIR__.'/Xml/Dom/Locator/Attributes/xmlns_attributes_list.php';
require_once __DIR__.'/Xml/Dom/Locator/Element/locate_by_namespaced_tag_name.php';
require_once __DIR__.'/Xml/Dom/Locator/Element/locate_by_tag_name.php';
require_once __DIR__.'/Xml/Dom/Locator/Namespaces/linked_namespaces.php';
require_once __DIR__.'/Xml/Dom/Locator/Namespaces/recursive_linked_namespaces.php';
require_once __DIR__.'/Xml/Dom/Locator/Node/ancestors.php';
require_once __DIR__.'/Xml/Dom/Locator/Node/children.php';
require_once __DIR__.'/Xml/Dom/Locator/Node/detect_document.php';
require_once __DIR__.'/Xml/Dom/Locator/Node/siblings.php';
require_once __DIR__.'/Xml/Dom/Locator/Node/value.php';
require_once __DIR__.'/Xml/Dom/Locator/Xsd/locate_all_xsd_schemas.php';
require_once __DIR__.'/Xml/Dom/Locator/Xsd/locate_namespaced_xsd_schemas.php';
require_once __DIR__.'/Xml/Dom/Locator/Xsd/locate_no_namespaced_xsd_schemas.php';
require_once __DIR__.'/Xml/Dom/Locator/document_element.php';
require_once __DIR__.'/Xml/Dom/Locator/elements_with_namespaced_tagname.php';
require_once __DIR__.'/Xml/Dom/Locator/elements_with_tagname.php';
require_once __DIR__.'/Xml/Dom/Manipulator/Node/append_external_node.php';
require_once __DIR__.'/Xml/Dom/Manipulator/Node/import_node_deeply.php';
require_once __DIR__.'/Xml/Dom/Manipulator/Node/remove.php';
require_once __DIR__.'/Xml/Dom/Manipulator/Node/replace_by_external_node.php';
require_once __DIR__.'/Xml/Dom/Manipulator/Node/replace_by_external_nodes.php';
require_once __DIR__.'/Xml/Dom/Manipulator/append.php';
require_once __DIR__.'/Xml/Dom/Mapper/xml_string.php';
require_once __DIR__.'/Xml/Dom/Mapper/xslt_template.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_attribute.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_default_xmlns_attribute.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_document.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_document_element.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_element.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_non_empty_text.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_text.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_whitespace.php';
require_once __DIR__.'/Xml/Dom/Predicate/is_xmlns_attribute.php';
require_once __DIR__.'/Xml/Dom/Traverser/Visitor/OptimizeNamespaces.php';
require_once __DIR__.'/Xml/Dom/Validator/internal_xsd_validator.php';
require_once __DIR__.'/Xml/Dom/Validator/validator_chain.php';
require_once __DIR__.'/Xml/Dom/Validator/xsd_validator.php';
require_once __DIR__.'/Xml/Dom/Xpath/Configurator/all_functions.php';
require_once __DIR__.'/Xml/Dom/Xpath/Configurator/functions.php';
require_once __DIR__.'/Xml/Dom/Xpath/Configurator/namespaces.php';
require_once __DIR__.'/Xml/Dom/Xpath/Configurator/php_namespace.php';
require_once __DIR__.'/Xml/Dom/Xpath/Locator/evaluate.php';
require_once __DIR__.'/Xml/Dom/Xpath/Locator/query.php';
require_once __DIR__.'/Xml/Dom/Xpath/Locator/query_single.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/attribute.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/attributes.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/element.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/group_child_elements.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/grouped_children.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/name.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/namespaces.php';
require_once __DIR__.'/Xml/Encoding/Internal/Decoder/Builder/unwrap_element.php';
require_once __DIR__.'/Xml/Encoding/Internal/Encoder/Builder/children.php';
require_once __DIR__.'/Xml/Encoding/Internal/Encoder/Builder/element.php';
require_once __DIR__.'/Xml/Encoding/Internal/Encoder/Builder/is_node_list.php';
require_once __DIR__.'/Xml/Encoding/Internal/Encoder/Builder/normalize_data.php';
require_once __DIR__.'/Xml/Encoding/Internal/Encoder/Builder/parent_node.php';
require_once __DIR__.'/Xml/Encoding/Internal/Encoder/Builder/root.php';
require_once __DIR__.'/Xml/Encoding/Internal/wrap_exception.php';
require_once __DIR__.'/Xml/Encoding/document_encode.php';
require_once __DIR__.'/Xml/Encoding/typed.php';
require_once __DIR__.'/Xml/Encoding/xml_decode.php';
require_once __DIR__.'/Xml/Encoding/xml_encode.php';
require_once __DIR__.'/Xml/ErrorHandling/Assertion/assert_strict_prefixed_name.php';
require_once __DIR__.'/Xml/ErrorHandling/detect_issues.php';
require_once __DIR__.'/Xml/ErrorHandling/disallow_issues.php';
require_once __DIR__.'/Xml/ErrorHandling/disallow_libxml_false_returns.php';
require_once __DIR__.'/Xml/ErrorHandling/issue_collection_from_xml_errors.php';
require_once __DIR__.'/Xml/ErrorHandling/issue_from_xml_error.php';
require_once __DIR__.'/Xml/ErrorHandling/issue_level_from_xml_error.php';
require_once __DIR__.'/Xml/ErrorHandling/stop_on_first_issue.php';
require_once __DIR__.'/Xml/Reader/Configurator/parser_options.php';
require_once __DIR__.'/Xml/Reader/Configurator/substitute_entities.php';
require_once __DIR__.'/Xml/Reader/Configurator/xsd_schema.php';
require_once __DIR__.'/Xml/Reader/Loader/xml_file_loader.php';
require_once __DIR__.'/Xml/Reader/Loader/xml_string_loader.php';
require_once __DIR__.'/Xml/Reader/Matcher/all.php';
require_once __DIR__.'/Xml/Reader/Matcher/node_attribute.php';
require_once __DIR__.'/Xml/Reader/Matcher/node_name.php';
require_once __DIR__.'/Xml/Writer/Builder/attribute.php';
require_once __DIR__.'/Xml/Writer/Builder/attributes.php';
require_once __DIR__.'/Xml/Writer/Builder/children.php';
require_once __DIR__.'/Xml/Writer/Builder/document.php';
require_once __DIR__.'/Xml/Writer/Builder/element.php';
require_once __DIR__.'/Xml/Writer/Builder/namespace_attribute.php';
require_once __DIR__.'/Xml/Writer/Builder/prefixed_attribute.php';
require_once __DIR__.'/Xml/Writer/Builder/prefixed_attributes.php';
require_once __DIR__.'/Xml/Writer/Builder/prefixed_element.php';
require_once __DIR__.'/Xml/Writer/Builder/value.php';
require_once __DIR__.'/Xml/Writer/Configurator/indentation.php';
require_once __DIR__.'/Xml/Writer/Configurator/open.php';
require_once __DIR__.'/Xml/Writer/Opener/xml_file_opener.php';
require_once __DIR__.'/Xml/Xsd/Schema/Manipulator/base_path.php';
require_once __DIR__.'/Xml/Xsd/Schema/Manipulator/overwrite_with_local_files.php';
require_once __DIR__.'/Xml/Xslt/Configurator/all_functions.php';
require_once __DIR__.'/Xml/Xslt/Configurator/functions.php';
require_once __DIR__.'/Xml/Xslt/Configurator/loader.php';
require_once __DIR__.'/Xml/Xslt/Configurator/parameters.php';
require_once __DIR__.'/Xml/Xslt/Configurator/profiler.php';
require_once __DIR__.'/Xml/Xslt/Configurator/security_preferences.php';
require_once __DIR__.'/Xml/Xslt/Loader/from_template_document.php';
require_once __DIR__.'/Xml/Xslt/Transformer/document_to_string.php';
