# Model Persistence
Model persistence is the ability to save and subsequently load a learner's state in another process. Trained estimators can be used for real-time inference by loading the model onto a server or they can be saved to make predictions offline at a later time. Estimators that implement the [Persistable](persistable.md) interface are able to have their internal state persisted between processes by a model [Persister](persisters/api.md). In addition, the library provides the [Persistent Model](persistent-model.md) meta-estimator that acts as a wrapper for persistable estimators.

## Persisters
Persisters are objects that interface with your storage backend such as a filesystem or Redis database. They provide the `save()` and `load()` methods which take and return persistable objects respectively. In order to function properly, persisters must have both read and write access to your storage system.

In the example below, the [Filesystem](persisters/filesystem.md) persister loads a persistable estimator from the filesystem, such as the system's local hard drive or network attached storage (NAS), and then saves it after performing some task.

**Example**

```php
use Rubix\ML\Persisters\Filesystem;

$persister = new Filesystem('example.model');

$estimator = $persister->load();

// Do something

$persister->save($estimator);
```

## Serialization
Model serialization occurs in between saving a loading and can be thought of as packaging a model's parameters into a single contiguous blob of data. The data can be in byte-stream format such as with PHP's [Native](persisters/serializers/native.md) serializer or in binary format as with the [Igbinary](persisters/serializers/igbinary.md) serializer. Knowing the format that a model was serialized in will allow you to transport the model between systems.

In the next example, we demonstrate how to replace the default serializer of the Filesystem persister with Igbinary format.

**Example**

```php
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Persisters\Serializers\Igbinary;

$persister = new Filesystem('example.model', true, new Igbinary());
```

> **Note:** Due to a limitation in PHP, anonymous classes and functions (*closures*) are not able to be deserialized. If you add anonymous classes or functions to the model, they must be given formal definitions before they can be persisted.

## Persistent Model Meta-estimator
The [Persistent Model](persistent-model.md) meta-estimator is a wrapper that uses the persistence subsystem under the hood. It provides the `save()` and `load()` methods that give the estimator the ability to save and load itself.

**Example**

```php
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

$estimator = PersistentModel::load(new Filesystem('example.model'));

// Do something

$estimator->save();
```
