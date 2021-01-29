import { useEffect } from 'react';
import { subscribe, unsubscribe } from 'pubsub-js';

const useSubscriber = (topic, subscriberCallback, deps = []) => {
  useEffect(() => {
    const token = subscribe(topic, subscriberCallback);

    return () => unsubscribe(token);
  }, deps);
};

export default useSubscriber;
